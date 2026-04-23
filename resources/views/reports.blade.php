<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Laporan Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-7xl mx-auto">
        @include('partials.dashboard-header', [
            'title' => 'Laporan Peminjaman Admin',
            'subtitle' => 'Admin dapat mengatur tarif denda per hari untuk telat, rusak, dan hilang.'
        ])

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-red-700">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-5">
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Sedang Dipinjam</p>
                <p class="mt-2 text-3xl font-bold text-amber-600">{{ $statusCounts['dipinjam'] }}</p>
            </div>
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Sudah Kembali</p>
                <p class="mt-2 text-3xl font-bold text-green-600">{{ $statusCounts['kembali'] }}</p>
            </div>
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Barang Rusak</p>
                <p class="mt-2 text-3xl font-bold text-orange-600">{{ $statusCounts['rusak'] }}</p>
            </div>
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Barang Hilang</p>
                <p class="mt-2 text-3xl font-bold text-rose-600">{{ $statusCounts['hilang'] }}</p>
            </div>
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Total Denda</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">Rp {{ number_format($totalFines, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-[1.1fr,1.4fr]">
            <div class="rounded-2xl bg-white p-6 shadow">
                <h2 class="mb-4 text-xl font-bold text-slate-900">Pengaturan Denda Per Hari</h2>
                <form action="{{ route('reports.penalty-settings') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold">Denda Telat / Hari</label>
                        <input type="number" min="0" name="fine_late_per_day" value="{{ old('fine_late_per_day', (int) $penaltyRates['telat']) }}" class="mt-1 w-full rounded border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold">Denda Rusak / Hari</label>
                        <input type="number" min="0" name="fine_damaged_per_day" value="{{ old('fine_damaged_per_day', (int) $penaltyRates['rusak']) }}" class="mt-1 w-full rounded border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold">Denda Hilang / Hari</label>
                        <input type="number" min="0" name="fine_lost_per_day" value="{{ old('fine_lost_per_day', (int) $penaltyRates['hilang']) }}" class="mt-1 w-full rounded border p-2">
                    </div>
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Simpan Tarif Denda</button>
                </form>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-900">Pendataan Barang</h2>
                    <a href="{{ route('items.index') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Kelola Barang</a>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @forelse($itemSummary as $item)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-400">{{ $item->type }}</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $item->name }}</p>
                            <p class="text-sm text-slate-500">Barcode/Kode: {{ $item->code }}</p>
                            <p class="mt-3 text-sm font-semibold {{ $item->stock > 0 ? 'text-green-600' : 'text-rose-600' }}">Stok tersedia: {{ $item->stock }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada data barang.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-md">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white text-left">
                        <th class="p-3">Waktu</th>
                        <th class="p-3">Siswa</th>
                        <th class="p-3">Identitas</th>
                        <th class="p-3">Barang/Buku</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Denda</th>
                        <th class="p-3">Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">
                            <span class="text-xs block text-gray-500 font-bold uppercase">Pinjam: {{ $report->borrow_date->format('d M Y') }}</span>
                            <span class="text-xs block text-blue-600">Batas: {{ $report->return_date->format('d M Y') }}</span>
                            <span class="text-xs block text-slate-500">Aktual: {{ $report->actual_return_date ? $report->actual_return_date->format('d M Y') : '-' }}</span>
                        </td>
                        <td class="p-3 font-medium">{{ $report->user->name }}</td>
                        <td class="p-3 text-sm text-slate-600">
                            NIS: {{ $report->user->nis ?? '-' }}<br>
                            {{ $report->user->rombel ?? '-' }} | {{ $report->user->rayon ?? '-' }}
                        </td>
                        <td class="p-3 italic text-gray-700">{{ $report->item->name }}<br><span class="text-xs text-gray-400">{{ $report->item->code }}</span></td>
                        <td class="p-3">
                            <span class="font-semibold {{ $report->status === 'kembali' ? 'text-green-600' : ($report->status === 'rusak' ? 'text-orange-600' : ($report->status === 'hilang' ? 'text-red-600' : 'text-gray-500')) }}">
                                {{ ucfirst($report->fine_reason === 'telat' ? 'telat' : $report->status) }}
                            </span>
                        </td>
                        <td class="p-3 text-sm text-slate-600">
                            @if($report->fine_amount > 0)
                                Rp {{ number_format($report->fine_amount, 0, ',', '.') }}<br>
                                <span class="text-xs text-slate-400">{{ $report->fine_days }} hari x Rp {{ number_format($report->fine_rate, 0, ',', '.') }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="p-3 text-sm text-gray-600">
                            {{ $report->officer->name ?? 'Admin' }}<br>
                            <span class="text-xs text-slate-400">{{ $report->officer->staff_id ?? 'ADMIN' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">Belum ada data transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
