<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\BorrowRequest;
use App\Models\Transaction;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller {
    public function index() {
        $activeTransactions = Transaction::with(['user', 'item'])->where('status', 'dipinjam')->latest()->get();
        $recentTransactions = Transaction::with(['user', 'item', 'officer'])->latest()->take(8)->get();
        $availableItems = Item::orderBy('name')->get();
        $students = User::where('role', 'siswa')->orderBy('name')->get();
        $officers = User::whereIn('role', ['admin', 'petugas'])->orderBy('name')->get();
        $pendingRequests = BorrowRequest::with(['user', 'item'])->where('status', 'pending')->latest()->get();
        $studentLookup = $students->map(function ($student) {
            return [
                'id' => (string) $student->id,
                'nis' => (string) ($student->nis ?? ''),
                'name' => $student->name,
                'rombel' => $student->rombel,
                'rayon' => $student->rayon,
            ];
        })->values();

        return view('petugas.index', compact('activeTransactions', 'recentTransactions', 'availableItems', 'students', 'officers', 'pendingRequests', 'studentLookup'));
    }

    public function borrow(Request $request) {
        $validated = $request->validate([
            'student_identifier' => 'required|string',
            'item_code' => 'required|string|exists:items,code',
            'duration' => 'required|integer|min:1'
        ]);

        $duration = (int) $validated['duration'];

        $student = User::where('role', 'siswa')
            ->where(function ($query) use ($request) {
                $query->where('id', $request->student_identifier)
                    ->orWhere('nis', $request->student_identifier);
            })
            ->first();

        if (! $student) {
            return back()->withInput()->withErrors([
                'student_identifier' => 'NIS atau ID siswa tidak ditemukan.',
            ]);
        }

        $item = Item::where('code', $request->item_code)->first();

        if ($item->stock <= 0) return back()->with('error', 'Stok habis!');

        Transaction::create([
            'user_id' => $student->id,
            'item_id' => $item->id,
            'officer_id' => Auth::id(),
            'borrow_date' => now()->toDateString(), // Pastikan format tanggal
            'return_date' => now()->addDays($duration)->toDateString(),
            'status' => 'dipinjam'
        ]);

        $item->decrement('stock');

        return redirect()->route('transactions.index')->with('success', 'Peminjaman berhasil!');
    }

    public function returnItem(Request $request, $id) {
        $validated = $request->validate(['status' => 'required|in:kembali,telat,hilang,rusak']);
        
        $transaction = Transaction::findOrFail($id);
        $item = Item::find($transaction->item_id);
        $actualReturnDate = now();
        $penaltyRates = $this->getPenaltyRates();
        $fineData = $this->calculateFineData($transaction, $validated['status'], $actualReturnDate, $penaltyRates);

        $transaction->update([
            'actual_return_date' => $actualReturnDate,
            'status' => $fineData['status'],
            'fine_reason' => $fineData['reason'],
            'fine_days' => $fineData['days'],
            'fine_rate' => $fineData['rate'],
            'fine_amount' => $fineData['amount'],
        ]);

        // Jika kembali (normal), stok ditambah lagi. Jika hilang/rusak parah, stok tidak kembali.
        if ($fineData['status'] === 'kembali') {
            $item->increment('stock');
        }

        $message = 'Status pengembalian dicatat sebagai: ' . $fineData['label'];

        if ($fineData['amount'] > 0) {
            $message .= ' dengan denda Rp ' . number_format($fineData['amount'], 0, ',', '.');
        }

        return back()->with('success', $message);
    }

    public function report() {
        // Mengambil semua transaksi dengan relasi user (Siswa) dan item
        $reports = Transaction::with(['user', 'item', 'officer'])->latest()->get();
        $statusCounts = [
            'dipinjam' => $reports->where('status', 'dipinjam')->count(),
            'kembali' => $reports->where('status', 'kembali')->count(),
            'rusak' => $reports->where('status', 'rusak')->count(),
            'hilang' => $reports->where('status', 'hilang')->count(),
        ];
        $itemSummary = Item::orderBy('name')->get();
        $penaltyRates = $this->getPenaltyRates();
        $totalFines = $reports->sum('fine_amount');
        $overdueReports = $reports->filter(fn (Transaction $transaction) => $transaction->is_overdue);
        $overdueCount = $overdueReports->count();
        $overdueFineProjection = $overdueReports->sum(function (Transaction $transaction) use ($penaltyRates) {
            return $transaction->overdue_days * $penaltyRates['telat'];
        });

        return view('admin.reports.index', compact('reports', 'statusCounts', 'itemSummary', 'penaltyRates', 'totalFines', 'overdueCount', 'overdueFineProjection')); // Mengarahkan ke folder admin/reports
    }

    public function updatePenaltySettings(Request $request)
    {
        $validated = $request->validate([
            'fine_late_per_day' => 'required|numeric|min:0',
            'fine_damaged_per_day' => 'required|numeric|min:0',
            'fine_lost_per_day' => 'required|numeric|min:0',
        ]);

        foreach ($validated as $key => $value) {
            AppSetting::putValue($key, (int) $value);
        }

        return redirect()->route('reports.index')->with('success', 'Tarif denda berhasil diperbarui.');
    }

    public function siswaHistory() {
        $myHistory = Transaction::where('user_id', Auth::id())->with('item')->latest()->get();
        $availableItems = Item::where('stock', '>', 0)->orderBy('name')->get();
        $myRequests = BorrowRequest::where('user_id', Auth::id())->with(['item', 'processor'])->latest()->get();

        return view('siswa.history.index', compact('myHistory', 'availableItems', 'myRequests')); // Mengarahkan ke folder siswa/history
    }

    public function submitBorrowRequest(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'duration' => 'required|integer|min:1|max:30',
            'notes' => 'nullable|string|max:500',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        if ($item->stock <= 0) {
            return back()->with('error', 'Stok barang sedang habis.');
        }

        $existingPending = BorrowRequest::where('user_id', Auth::id())
            ->where('item_id', $item->id)
            ->where('status', 'pending')
            ->exists();

        if ($existingPending) {
            return back()->with('error', 'Pengajuan untuk barang ini masih menunggu proses petugas.');
        }

        BorrowRequest::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'duration' => (int) $validated['duration'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Pengajuan peminjaman berhasil dikirim ke petugas.');
    }

    public function processBorrowRequest(Request $request, BorrowRequest $borrowRequest)
    {
        $validated = $request->validate([
            'action' => 'required|in:approved,rejected',
        ]);

        if ($borrowRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $borrowRequest->load(['user', 'item']);

        if ($validated['action'] === 'approved') {
            if ($borrowRequest->item->stock <= 0) {
                return back()->with('error', 'Stok barang habis. Pengajuan tidak bisa disetujui.');
            }

            Transaction::create([
                'user_id' => $borrowRequest->user_id,
                'item_id' => $borrowRequest->item_id,
                'officer_id' => Auth::id(),
                'borrow_date' => now()->toDateString(),
                'return_date' => now()->addDays((int) $borrowRequest->duration)->toDateString(),
                'status' => 'dipinjam',
            ]);

            $borrowRequest->item->decrement('stock');
        }

        $borrowRequest->update([
            'status' => $validated['action'],
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return back()->with('success', $validated['action'] === 'approved'
            ? 'Pengajuan berhasil disetujui dan dipindahkan menjadi transaksi pinjam.'
            : 'Pengajuan berhasil ditolak.');
    }

    private function getPenaltyRates(): array
    {
        return [
            'telat' => (float) AppSetting::getValue('fine_late_per_day', 5000),
            'rusak' => (float) AppSetting::getValue('fine_damaged_per_day', 10000),
            'hilang' => (float) AppSetting::getValue('fine_lost_per_day', 15000),
        ];
    }

    private function calculateFineData(Transaction $transaction, string $requestedStatus, Carbon $actualReturnDate, array $penaltyRates): array
    {
        $overdueDays = max(0, $transaction->return_date->diffInDays($actualReturnDate, false));
        $reason = null;
        $status = $requestedStatus;
        $rate = 0;
        $days = 0;

        if ($requestedStatus === 'hilang' || $requestedStatus === 'rusak') {
            $reason = $requestedStatus;
            $rate = $penaltyRates[$requestedStatus] ?? 0;
            $days = max(1, $overdueDays);
        } elseif ($requestedStatus === 'telat' || ($requestedStatus === 'kembali' && $overdueDays > 0)) {
            $reason = 'telat';
            $status = 'kembali';
            $rate = $penaltyRates['telat'] ?? 0;
            $days = max(1, $overdueDays);
        } else {
            $status = 'kembali';
        }

        return [
            'status' => $status,
            'reason' => $reason,
            'days' => $days,
            'rate' => $rate,
            'amount' => $days * $rate,
            'label' => $reason ? $reason : $status,
        ];
    }
}
