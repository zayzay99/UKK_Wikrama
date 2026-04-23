<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petugas - Kelola Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-5">
    <div class="mx-auto max-w-7xl">
        @include('partials.dashboard-header', [
            'title' => 'Panel Petugas Peminjaman',
            'subtitle' => 'Scan NIS siswa dan barcode barang, lalu transaksi otomatis tercatat ke laporan admin.'
        ])

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-red-700">
                {{ session('error') }}
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

        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Pinjaman Aktif</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $activeTransactions->count() }}</p>
            </div>
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Stok Tersedia</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $availableItems->sum('stock') }}</p>
            </div>
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Data Siswa</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $students->count() }}</p>
            </div>
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Petugas Login</p>
                <p class="mt-2 text-lg font-bold text-slate-900">{{ Auth::user()->staff_id ?? 'ADMIN' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[380px,1fr]">
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-lg font-bold mb-4">Input Peminjaman</h2>
                    <form action="{{ route('transactions.borrow') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold">Scan NIS / ID Siswa</label>
                            <input type="text" name="student_identifier" id="student_identifier" value="{{ old('student_identifier') }}" class="mt-1 w-full border p-2 rounded bg-gray-50 focus:border-blue-500 outline-none" placeholder="Contoh: 12230001 atau ID siswa" required>
                        </div>
                        <div id="student-preview" class="rounded-lg bg-slate-50 p-3 text-sm text-slate-600">
                            Scan NIS siswa untuk melihat data peminjam.
                        </div>
                        <div>
                            <label class="block text-sm font-semibold">Scan Barcode Barang / Buku</label>
                            <input type="text" name="item_code" id="item_code" value="{{ old('item_code') }}" class="mt-1 w-full border p-2 rounded bg-gray-50 focus:border-blue-500 outline-none" placeholder="Contoh: BK-001 atau ALT-002" required>
                        </div>
                        <div id="item-preview" class="rounded-lg bg-slate-50 p-3 text-sm text-slate-600">
                            Scan barcode barang untuk memilih item yang akan dipinjam.
                        </div>
                        <div>
                            <label class="block text-sm font-semibold">Durasi Peminjaman (hari)</label>
                            <input type="number" name="duration" min="1" value="{{ old('duration', 3) }}" class="mt-1 w-full border p-2 rounded bg-gray-50 focus:border-blue-500 outline-none" required>
                        </div>
                        <button class="w-full bg-blue-600 text-white py-2 rounded font-bold uppercase">Simpan Peminjaman</button>
                    </form>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="mb-3 font-semibold text-slate-900">Referensi Siswa</h3>
                    <div class="space-y-2 text-sm max-h-80 overflow-auto">
                        @forelse($students as $student)
                            <div class="rounded-lg border border-slate-200 p-3">
                                <p class="font-semibold">{{ $student->name }}</p>
                                <p class="text-xs text-slate-500">NIS: {{ $student->nis ?? '-' }} | ID: {{ $student->id }}</p>
                                <p class="text-xs text-slate-500">{{ $student->rombel }} | {{ $student->rayon }}</p>
                            </div>
                        @empty
                            <p class="text-slate-500">Belum ada data siswa.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-lg font-bold mb-4">Pengajuan dari Siswa</h2>
                    <div class="space-y-3 mb-8">
                        @forelse($pendingRequests as $request)
                            <div class="rounded-lg border border-slate-200 p-4">
                                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $request->user->name }} mengajukan {{ $request->item->name }}</p>
                                        <p class="text-sm text-slate-500">NIS {{ $request->user->nis ?? '-' }} | Durasi {{ $request->duration }} hari | {{ $request->created_at->format('d M Y H:i') }}</p>
                                        @if($request->notes)
                                            <p class="text-sm text-slate-500">Catatan: {{ $request->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <form action="{{ route('transactions.requests.process', $request) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="approved">
                                            <button class="rounded bg-green-600 px-3 py-2 text-xs font-semibold text-white">Setujui</button>
                                        </form>
                                        <form action="{{ route('transactions.requests.process', $request) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="rejected">
                                            <button class="rounded bg-rose-600 px-3 py-2 text-xs font-semibold text-white">Tolak</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada pengajuan dari siswa.</p>
                        @endforelse
                    </div>

                    <h2 class="text-lg font-bold mb-4">Daftar Barang dan Buku</h2>
                    <p class="mb-4 text-sm text-slate-500">Saat barcode discan, daftar di bawah langsung menyorot item yang cocok.</p>
                    <div id="item-list" class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        @forelse($availableItems as $item)
                            <button
                                type="button"
                                class="item-card rounded-lg border border-slate-200 p-4 text-left transition hover:border-blue-500 hover:bg-blue-50"
                                data-code="{{ strtolower($item->code) }}"
                                data-name="{{ strtolower($item->name) }}"
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $item->name }}</p>
                                        <p class="text-sm text-slate-500">{{ strtoupper($item->type) }} | {{ $item->code }}</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $item->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-rose-100 text-rose-700' }}">
                                        Stok {{ $item->stock }}
                                    </span>
                                </div>
                            </button>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada data barang.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-lg font-bold mb-4">Daftar Pinjaman Aktif</h2>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 uppercase text-xs">
                            <tr>
                                <th class="p-2 text-left">Peminjam</th>
                                <th class="p-2 text-left">Barang</th>
                                <th class="p-2 text-left">Batas</th>
                                <th class="p-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeTransactions as $trx)
                            <tr class="border-t">
                                <td class="p-2 font-bold">{{ $trx->user->name }}<br><span class="text-xs font-normal text-gray-400">NIS {{ $trx->user->nis ?? '-' }}</span></td>
                                <td class="p-2">{{ $trx->item->name }}<br><span class="text-xs text-gray-400">{{ $trx->item->code }}</span></td>
                                <td class="p-2 text-xs text-slate-500">{{ $trx->return_date->format('d M Y') }}</td>
                                <td class="p-2">
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        <form action="{{ route('transactions.return', $trx->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="kembali">
                                            <button class="bg-green-500 text-white px-2 py-1 rounded text-xs">Kembali</button>
                                        </form>
                                        <form action="{{ route('transactions.return', $trx->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="telat">
                                            <button class="bg-amber-500 text-white px-2 py-1 rounded text-xs">Telat</button>
                                        </form>
                                        <form action="{{ route('transactions.return', $trx->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="rusak">
                                            <button class="bg-orange-500 text-white px-2 py-1 rounded text-xs">Rusak</button>
                                        </form>
                                        <form action="{{ route('transactions.return', $trx->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="hilang">
                                            <button class="bg-red-500 text-white px-2 py-1 rounded text-xs">Hilang</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">Belum ada pinjaman aktif.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="mb-4 text-lg font-bold">Transaksi Terbaru</h3>
                    <div class="space-y-3">
                        @forelse($recentTransactions as $trx)
                            <div class="rounded-lg border border-slate-200 p-4">
                                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $trx->user->name }} meminjam {{ $trx->item->name }}</p>
                                        <p class="text-sm text-slate-500">NIS {{ $trx->user->nis ?? '-' }} | Petugas {{ $trx->officer->staff_id ?? ($trx->officer->name ?? '-') }}</p>
                                        @if($trx->fine_amount > 0)
                                            <p class="text-sm font-semibold text-rose-600">Denda {{ ucfirst($trx->fine_reason ?? '-') }}: Rp {{ number_format($trx->fine_amount, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $trx->status === 'dipinjam' ? 'bg-amber-100 text-amber-700' : ($trx->status === 'kembali' ? 'bg-green-100 text-green-700' : 'bg-rose-100 text-rose-700') }}">
                                        {{ strtoupper($trx->fine_reason === 'telat' ? 'telat' : $trx->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada transaksi.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const studentInput = document.getElementById('student_identifier');
        const itemInput = document.getElementById('item_code');
        const studentPreview = document.getElementById('student-preview');
        const itemPreview = document.getElementById('item-preview');
        const itemCards = Array.from(document.querySelectorAll('.item-card'));
        const students = @json($studentLookup);

        function updateStudentPreview() {
            const value = studentInput.value.trim();
            const student = students.find((entry) => entry.id === value || entry.nis === value);

            if (!value) {
                studentPreview.textContent = 'Scan NIS siswa untuk melihat data peminjam.';
                return;
            }

            if (!student) {
                studentPreview.textContent = 'Data siswa belum ditemukan. Pastikan NIS atau ID benar.';
                return;
            }

            studentPreview.innerHTML = `<strong>${student.name}</strong><br>NIS: ${student.nis || '-'} | ID: ${student.id}<br>${student.rombel || '-'} | ${student.rayon || '-'}`;
        }

        function updateItemPreview() {
            const value = itemInput.value.trim().toLowerCase();
            let found = false;

            itemCards.forEach((card) => {
                const matches = value && (card.dataset.code.includes(value) || card.dataset.name.includes(value));
                card.classList.toggle('border-blue-600', matches);
                card.classList.toggle('bg-blue-50', matches);
                card.classList.toggle('ring-2', matches);
                card.classList.toggle('ring-blue-200', matches);
                card.classList.toggle('hidden', value && !matches);
                if (matches) {
                    found = true;
                }
            });

            if (!value) {
                itemCards.forEach((card) => card.classList.remove('hidden'));
                itemPreview.textContent = 'Scan barcode barang untuk memilih item yang akan dipinjam.';
                return;
            }

            if (!found) {
                itemCards.forEach((card) => card.classList.remove('hidden'));
                itemPreview.textContent = 'Barcode belum cocok dengan data barang.';
                return;
            }

            itemPreview.textContent = 'Item ditemukan. Periksa daftar yang tersorot lalu simpan peminjaman.';
        }

        itemCards.forEach((card) => {
            card.addEventListener('click', () => {
                const codeText = card.dataset.code;
                itemInput.value = codeText.toUpperCase();
                updateItemPreview();
            });
        });

        studentInput.addEventListener('input', updateStudentPreview);
        itemInput.addEventListener('input', updateItemPreview);

        updateStudentPreview();
        updateItemPreview();
    </script>
</body>
</html>
