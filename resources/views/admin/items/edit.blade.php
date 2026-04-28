<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Item</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-md mx-auto">
        @include('partials.dashboard-header', [
            'title' => 'Edit Data Barang',
            'subtitle' => 'Perbarui stok, kode, atau jenis barang yang tersedia.'
        ])

        <div class="bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Item</h1>

            @include('partials.flash-alerts')

            <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="block text-sm font-bold mb-2">Nama Item</label>
                    <input type="text" name="name" id="name" class="w-full border p-2 rounded" value="{{ old('name', $item->name) }}" required>
                </div>
                <div class="mb-4">
                    <label for="type" class="block text-sm font-bold mb-2">Tipe</label>
                    <select name="type" id="type" class="w-full border p-2 rounded" required>
                        <option value="buku" {{ old('type', $item->type) == 'buku' ? 'selected' : '' }}>Buku</option>
                        <option value="alat" {{ old('type', $item->type) == 'alat' ? 'selected' : '' }}>Alat</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="code" class="block text-sm font-bold mb-2">Kode Barcode</label>
                    <input type="text" name="code" id="code" class="w-full border p-2 rounded" value="{{ old('code', $item->code) }}" placeholder="Kosongkan jika ingin tetap memakai kode lama">
                    <p class="mt-1 text-xs text-slate-500">Biarkan terisi untuk mempertahankan barcode lama, atau ganti manual jika diperlukan.</p>
                </div>
                <div class="mb-6">
                    <label for="stock" class="block text-sm font-bold mb-2">Stok</label>
                    <input type="number" name="stock" id="stock" min="0" class="w-full border p-2 rounded" value="{{ old('stock', $item->stock) }}" required>
                </div>
                <div class="mb-6">
                    <label for="image" class="block text-sm font-bold mb-2">Gambar/Cover</label>
                    @if($item->image)
                        <div class="mb-3">

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
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="rounded w-full max-w-xs">
                        </div>
                    @endif
                    <input type="file" name="image" id="image" class="w-full border p-2 rounded" accept="image/*">
                    <p class="mt-1 text-xs text-slate-500">Format: JPG, PNG, GIF (Max: 2MB). Kosongkan jika tidak ingin mengubah gambar.</p>
                    <img id="imagePreview" class="mt-3 hidden rounded w-full max-w-xs" alt="Preview">
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded font-bold">Update</button>
            </form>
        </div>
    </div>
</body>
</html>
