<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manajemen Item</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-6xl mx-auto">
        @include('partials.dashboard-header', [
            'title' => 'Pendataan Barang dan Buku',
            'subtitle' => 'Admin mengelola stok buku dan barang yang dipinjam lewat petugas.'
        ])

        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Item</h1>
                <a href="{{ route('items.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tambah Item</a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white text-left">
                        <th class="p-3">Nama</th>
                        <th class="p-3">Tipe</th>
                        <th class="p-3">Kode</th>
                        <th class="p-3">Stok</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $item->name }}</td>
                            <td class="p-3 uppercase">{{ $item->type }}</td>
                            <td class="p-3">{{ $item->code }}</td>
                            <td class="p-3">{{ $item->stock }}</td>
                            <td class="p-3 flex gap-2">
                                <a href="{{ route('items.edit', $item) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">Edit</a>
                                <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">Belum ada item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
