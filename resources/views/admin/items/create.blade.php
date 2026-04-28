<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tambah Item</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-md mx-auto">
        @include('partials.dashboard-header', [
            'title' => 'Tambah Data Barang',
            'subtitle' => 'Tambahkan buku atau alat agar bisa dipinjam oleh siswa.'
        ])

        <div class="bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Item</h1>

            @include('partials.flash-alerts')

            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-bold mb-2">Nama Item</label>
                    <input type="text" name="name" id="name" class="w-full border p-2 rounded" value="{{ old('name') }}" required>
                </div>
                <div class="mb-4">
                    <label for="type" class="block text-sm font-bold mb-2">Tipe</label>
                    <select name="type" id="type" class="w-full border p-2 rounded" required>
                        <option value="">Pilih Tipe</option>
                        <option value="buku" {{ old('type') == 'buku' ? 'selected' : '' }}>Buku</option>
                        <option value="alat" {{ old('type') == 'alat' ? 'selected' : '' }}>Alat</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="code" class="block text-sm font-bold mb-2">Kode Barcode</label>
                    <input type="text" name="code" id="code" class="w-full border p-2 rounded" value="{{ old('code') }}" placeholder="Kosongkan jika ingin generate otomatis">
                    <p class="mt-1 text-xs text-slate-500">Barcode akan dibuat otomatis jika field ini dikosongkan.</p>
                </div>
                <div class="mb-4">
                    <label for="stock" class="block text-sm font-bold mb-2">Stok</label>
                    <input type="number" name="stock" id="stock" min="0" class="w-full border p-2 rounded" value="{{ old('stock', 0) }}" required>
                </div>
                <div class="mb-6">
                    <label for="image" class="block text-sm font-bold mb-2">Gambar/Cover</label>
                    <input type="file" name="image" id="image" class="w-full border p-2 rounded" accept="image/*">
                    <p class="mt-1 text-xs text-slate-500">Format: JPG, PNG, GIF (Max: 2MB)</p>
                    <img id="imagePreview" class="mt-3 hidden rounded w-full max-w-xs" alt="Preview">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-bold">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
