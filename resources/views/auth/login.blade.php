<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa - UKK Wikrama</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 px-4 py-10">
    <div class="mx-auto grid w-full max-w-5xl gap-8 lg:grid-cols-[1.1fr,0.9fr]">
        <section class="rounded-2xl bg-gradient-to-br from-blue-700 to-cyan-500 p-8 text-white shadow-lg">
            <div class="flex h-full flex-col justify-between gap-8">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-100">Portal Siswa & Petugas</p>
                    <h1 class="mt-4 text-3xl font-bold leading-tight">Aplikasi Peminjaman Buku dan Alat</h1>
                    <p class="mt-4 max-w-xl text-sm text-blue-50">
                        Halaman ini dipakai siswa dan petugas untuk masuk dan membuat akun baru. Admin masuk dari halaman terpisah.
                    </p>
                </div>
{{-- 
                <div class="rounded-2xl bg-white/10 p-5 backdrop-blur-sm">
                    <p class="text-sm font-semibold">Akses internal</p>
                    <p class="mt-2 text-sm text-blue-50">Untuk admin atau petugas, gunakan halaman login internal.</p>
                    <a href="{{ route('admin.login') }}" class="mt-4 inline-flex rounded-lg bg-white px-4 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-50">
                        Buka Login Admin
                    </a>
                </div> --}}
            </div>
        </section>

        <section class="rounded-2xl bg-white p-8 shadow-lg">
            <div class="mb-6 flex rounded-xl bg-slate-100 p-1">
                <button type="button" data-tab-trigger="login-siswa" class="tab-trigger flex-1 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700">Login Siswa</button>
                <button type="button" data-tab-trigger="login-petugas" class="tab-trigger flex-1 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700">Login Petugas</button>
                <button type="button" data-tab-trigger="register" class="tab-trigger flex-1 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700">Register Siswa</button>
            </div>

            @include('partials.flash-alerts')

            <div data-tab-panel="login-siswa">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-slate-800">Masuk sebagai siswa</h2>
                    <p class="mt-1 text-sm text-slate-500">Gunakan email dan password akun siswa Anda.</p>
                </div>

                <form action="{{ route('student.login') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-blue-500" placeholder="siswa@wikrama.sch.id" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                        <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-blue-500" placeholder="********" required>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-blue-600 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">
                        LOGIN SISWA
                    </button>
                </form>
            </div>

            <div data-tab-panel="login-petugas" class="hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-slate-800">Masuk sebagai petugas</h2>
                    <p class="mt-1 text-sm text-slate-500">Gunakan email dan password akun petugas Anda.</p>
                </div>

                <form action="{{ route('petugas.login.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-blue-500" placeholder="petugas@wikrama.sch.id" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                        <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-blue-500" placeholder="********" required>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-orange-500 py-2.5 text-sm font-bold text-white transition hover:bg-orange-600">
                        LOGIN PETUGAS
                    </button>
                </form>
            </div>

            <div data-tab-panel="register" class="hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-slate-800">Daftar akun siswa</h2>
                    <p class="mt-1 text-sm text-slate-500">Lengkapi data untuk membuat akun siswa baru.</p>
                </div>

                <form action="{{ route('student.register') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-blue-500" required>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">NIS</label>
                            <input type="text" name="nis" value="{{ old('nis') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-blue-500" required>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Rayon</label>
                            <input type="text" name="rayon" value="{{ old('rayon') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Rombel</label>
                            <input type="text" name="rombel" value="{{ old('rombel') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-blue-500" required>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                            <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-blue-500" required>
                        </div>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-slate-900 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800">
                        REGISTER SISWA
                    </button>
                </form>
            </div>

            <p class="mt-8 text-center text-xs text-slate-400">&copy; 2025 SMK Wikrama Bogor - UKK Project</p>
        </section>
    </div>

    <script>
        (function () {
            const triggers = document.querySelectorAll('[data-tab-trigger]');
            const panels = document.querySelectorAll('[data-tab-panel]');
            const hasRegisterErrors = @json($errors->has('nis') || $errors->has('rayon') || $errors->has('rombel') || $errors->has('password_confirmation') || old('nis'));

            function activateTab(tab) {
                triggers.forEach((trigger) => {
                    const isActive = trigger.dataset.tabTrigger === tab;
                    trigger.classList.toggle('bg-white', isActive);
                    trigger.classList.toggle('text-slate-900', isActive);
                    trigger.classList.toggle('shadow-sm', isActive);
                });

                panels.forEach((panel) => {
                    panel.classList.toggle('hidden', panel.dataset.tabPanel !== tab);
                });
            }

            triggers.forEach((trigger) => {
                trigger.addEventListener('click', () => activateTab(trigger.dataset.tabTrigger));
            });

            activateTab(hasRegisterErrors ? 'register' : 'login-siswa');
        })();
    </script>
</body>
</html>
