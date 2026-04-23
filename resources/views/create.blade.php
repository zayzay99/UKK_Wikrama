<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tambah User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah User Baru</h1>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama</label>
                <input type="text" name="name" id="name" class="w-full border p-2 rounded focus:border-blue-500 outline-none" value="{{ old('name') }}" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" id="email" class="w-full border p-2 rounded focus:border-blue-500 outline-none" value="{{ old('email') }}" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" id="password" class="w-full border p-2 rounded focus:border-blue-500 outline-none" required>
            </div>
            <div class="mb-4">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                <select name="role" id="role" class="w-full border p-2 rounded focus:border-blue-500 outline-none" required>
                    <option value="">Pilih Role</option>
                    <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="staff_id" class="block text-gray-700 text-sm font-bold mb-2">ID Petugas</label>
                <input type="text" name="staff_id" id="staff_id" class="w-full border p-2 rounded focus:border-blue-500 outline-none" value="{{ old('staff_id') }}" placeholder="Contoh: PTG-002">
            </div>
            <div class="mb-4">
                <label for="nis" class="block text-gray-700 text-sm font-bold mb-2">NIS Siswa</label>
                <input type="text" name="nis" id="nis" class="w-full border p-2 rounded focus:border-blue-500 outline-none" value="{{ old('nis') }}" placeholder="Contoh: 12230010">
            </div>
            <div class="mb-4">
                <label for="rayon" class="block text-gray-700 text-sm font-bold mb-2">Rayon (Untuk Siswa)</label>
                <input type="text" name="rayon" id="rayon" class="w-full border p-2 rounded focus:border-blue-500 outline-none" value="{{ old('rayon') }}">
            </div>
            <div class="mb-6">
                <label for="rombel" class="block text-gray-700 text-sm font-bold mb-2">Rombel (Untuk Siswa)</label>
                <input type="text" name="rombel" id="rombel" class="w-full border p-2 rounded focus:border-blue-500 outline-none" value="{{ old('rombel') }}">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-bold uppercase hover:bg-blue-700">Simpan User</button>
            <a href="{{ route('users.index') }}" class="block text-center mt-4 text-blue-600 hover:underline">Kembali ke Daftar User</a>
        </form>
    </div>
</body>
</html>
