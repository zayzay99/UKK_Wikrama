<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa - Riwayat Pinjam</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-5">
    <div class="max-w-6xl mx-auto">
        @include('partials.dashboard-header', [
            'title' => 'Portal Peminjaman Siswa',
            'subtitle' => 'Lihat daftar barang yang tersedia lalu ajukan peminjaman ke petugas.'
        ])

        @include('partials.flash-alerts')

        <div class="mb-6 rounded-xl bg-white p-6 shadow">
            <div class="mb-6 flex flex-col gap-1 border-b border-slate-100 pb-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">{{ Auth::user()->name }}</h3>
                    <p class="text-sm text-slate-500">NIS {{ Auth::user()->nis ?? '-' }} | {{ Auth::user()->rombel }} | {{ Auth::user()->rayon }}</p>
                </div>
                <span class="inline-flex w-fit rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase text-slate-700">Portal Siswa</span>
            </div>

            <div>
                <h3 class="mb-4 text-lg font-bold text-slate-900">Pengajuan Peminjaman</h3>
                <p class="mb-4 text-sm text-slate-500">Pilih barang yang tersedia, tentukan durasi, lalu kirim pengajuan ke petugas.</p>
                <form action="{{ route('siswa.requests.store') }}" method="POST" class="grid grid-cols-1 gap-4 md:grid-cols-[1.4fr,0.6fr]">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold">Barang / Buku</label>
                        <select name="item_id" class="mt-1 w-full rounded border p-2" required>
                            <option value="">Pilih barang yang ingin dipinjam</option>
                            @foreach($availableItems as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} ({{ strtoupper($item->type) }} | {{ $item->code }} | Stok {{ $item->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold">Durasi (hari)</label>
                        <input type="number" name="duration" min="1" max="30" value="{{ old('duration', 3) }}" class="mt-1 w-full rounded border p-2" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold">Catatan</label>
                        <textarea name="notes" rows="3" class="mt-1 w-full rounded border p-2" placeholder="Contoh: untuk tugas kelompok / praktikum">{{ old('notes') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Ajukan ke Petugas</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mb-6 rounded-xl bg-white p-6 shadow">
            <h3 class="mb-4 text-lg font-bold text-slate-900">Daftar Barang dan Buku yang Bisa Dipinjam</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @forelse($availableItems as $item)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400">{{ $item->type }}</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $item->name }}</p>
                        <p class="text-sm text-slate-500">Kode: {{ $item->code }}</p>
                        <p class="mt-3 text-sm font-semibold text-green-600">Stok tersedia: {{ $item->stock }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada barang yang tersedia.</p>
                @endforelse
            </div>
        </div>

        <div class="mb-6 rounded-xl bg-white p-6 shadow">
            <h3 class="mb-4 text-lg font-bold text-slate-900">Status Pengajuan Saya</h3>
            <div class="space-y-3">
                @forelse($myRequests as $request)
                    <div class="rounded-lg border border-slate-200 p-4">
                        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $request->item->name }}</p>
                                <p class="text-sm text-slate-500">Durasi {{ $request->duration }} hari | Diajukan {{ $request->created_at->format('d M Y H:i') }}</p>
                                @if($request->notes)
                                    <p class="text-sm text-slate-500">Catatan: {{ $request->notes }}</p>
                                @endif
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $request->status === 'approved' ? 'bg-green-100 text-green-700' : ($request->status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ strtoupper($request->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada pengajuan peminjaman.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow">
            <h4 class="font-bold border-b mb-3 pb-2 text-gray-700">Riwayat Peminjaman</h4>
            @forelse($myHistory as $history)
            <div class="mb-4 border-b pb-3 text-sm">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold">{{ $history->item->name }}</p>
                        <p class="text-xs text-gray-500 italic">{{ $history->borrow_date->format('d M Y') }} | Batas kembali {{ $history->return_date->format('d M Y') }}</p>
                    </div>
                    <span class="uppercase text-[10px] font-bold px-2 py-1 rounded {{ $history->status == 'kembali' ? 'bg-green-100 text-green-700' : ($history->status == 'dipinjam' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700') }}">{{ $history->fine_reason === 'telat' ? 'telat' : $history->status }}</span>
                </div>
                @if($history->fine_amount > 0)
                    <p class="mt-2 text-xs font-semibold text-rose-600">Denda: Rp {{ number_format($history->fine_amount, 0, ',', '.') }} ({{ $history->fine_days }} hari x Rp {{ number_format($history->fine_rate, 0, ',', '.') }})</p>
                @endif
            </div>
            @empty
            <p class="text-sm text-gray-500">Belum ada riwayat peminjaman.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
