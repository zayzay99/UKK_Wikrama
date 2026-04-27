<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - UKK Wikrama</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 px-4 py-10">
    <div class="mx-auto grid w-full max-w-5xl gap-8 lg:grid-cols-[0.95fr,1.05fr]">
        <section class="rounded-2xl border border-slate-800 bg-slate-900 p-8 text-white shadow-2xl">
            <div class="flex h-full flex-col justify-between gap-8">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-300">Portal Admin</p>
                    <h1 class="mt-4 text-3xl font-bold leading-tight">Login Admin</h1>
                    <p class="mt-4 max-w-xl text-sm text-slate-300">
                        Halaman ini khusus untuk admin. Petugas memiliki halaman login terpisah.
                    </p>
                </div>
{{-- 
                <div class="rounded-2xl border border-slate-700 bg-slate-800/70 p-5">
                    <p class="text-sm font-semibold text-white">Portal siswa</p>
                    <p class="mt-2 text-sm text-slate-300">Siswa masuk dan daftar dari halaman login utama.</p>
                    <a href="{{ route('login') }}" class="mt-4 inline-flex rounded-lg bg-cyan-400 px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300">
                        Buka Login Siswa
                    </a>
                </div> --}}
            </div>
        </section>

        <section class="rounded-2xl bg-white p-8 shadow-2xl">
            <div class="mb-6 flex rounded-xl bg-slate-100 p-1">
                <button type="button" data-tab-trigger="login" class="tab-trigger flex-1 rounded-lg px-4 py-2 text-sm font-semibold text-slate-700">Login Admin</button>
                <button type="button" data-tab-trigger="register" class="tab-trigger flex-1 rounded-lg px-4 py-2 text-sm font-semibold text-slate-700">Register Admin</button>
            </div>

            @include('partials.flash-alerts')

            <div data-tab-panel="login">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-slate-800">Masuk sebagai Admin</h2>
                    <p class="mt-1 text-sm text-slate-500">Gunakan email dan password akun admin Anda.</p>
                </div>

                <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-cyan-500" placeholder="admin@wikrama.sch.id" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                        <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-cyan-500" placeholder="********" required>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-slate-950 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800">
                        LOGIN ADMIN
                    </button>
                </form>
            </div>

            <div data-tab-panel="register" class="hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-slate-800">Buat akun admin</h2>
                    <p class="mt-1 text-sm text-slate-500">Registrasi ini hanya akan membuat user dengan role admin.</p>
                </div>

                <form action="{{ route('admin.register') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-cyan-500" required>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-cyan-500" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Staff ID</label>
                            <input type="text" name="staff_id" value="{{ old('staff_id') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-cyan-500" required>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                            <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-cyan-500" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none transition focus:border-cyan-500" required>
                        </div>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-cyan-500 py-2.5 text-sm font-bold text-slate-950 transition hover:bg-cyan-400">
                        REGISTER ADMIN
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
            const hasRegisterErrors = @json($errors->has('staff_id') || $errors->has('password_confirmation') || old('staff_id'));

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

            activateTab(hasRegisterErrors ? 'register' : 'login');
        })();
    </script>
</body>
</html>
