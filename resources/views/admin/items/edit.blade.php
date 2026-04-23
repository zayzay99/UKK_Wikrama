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

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('items.update', $item) }}" method="POST">
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
                    <label for="code" class="block text-sm font-bold mb-2">Kode</label>
                    <input type="text" name="code" id="code" class="w-full border p-2 rounded" value="{{ old('code', $item->code) }}" required>
                </div>
                <div class="mb-6">
                    <label for="stock" class="block text-sm font-bold mb-2">Stok</label>
                    <input type="number" name="stock" id="stock" min="0" class="w-full border p-2 rounded" value="{{ old('stock', $item->stock) }}" required>
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded font-bold">Update</button>
            </form>
        </div>
    </div>
</body>
</html>
