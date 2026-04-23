<div class="mb-6 rounded-2xl bg-slate-900 text-white shadow-lg">
    <div class="flex flex-col gap-4 px-6 py-5 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-300">UKK Wikrama</p>
            <h1 class="text-2xl font-bold">{{ $title }}</h1>
            @isset($subtitle)
                <p class="mt-1 text-sm text-slate-300">{{ $subtitle }}</p>
            @endisset
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('reports.index') }}" class="rounded-lg px-3 py-2 text-sm {{ request()->routeIs('reports.index') ? 'bg-white text-slate-900' : 'bg-slate-800 text-white hover:bg-slate-700' }}">Laporan</a>
                <a href="{{ route('items.index') }}" class="rounded-lg px-3 py-2 text-sm {{ request()->routeIs('items.*') ? 'bg-white text-slate-900' : 'bg-slate-800 text-white hover:bg-slate-700' }}">Data Barang</a>
                <a href="{{ route('users.index') }}" class="rounded-lg px-3 py-2 text-sm {{ request()->routeIs('users.*') ? 'bg-white text-slate-900' : 'bg-slate-800 text-white hover:bg-slate-700' }}">Data User</a>
                <a href="{{ route('transactions.index') }}" class="rounded-lg bg-amber-400 px-3 py-2 text-sm font-semibold text-slate-900 hover:bg-amber-300">Panel Petugas</a>
            @endif
            @if(Auth::user()->role === 'petugas')
                <a href="{{ route('transactions.index') }}" class="rounded-lg px-3 py-2 text-sm {{ request()->routeIs('transactions.*') ? 'bg-white text-slate-900' : 'bg-slate-800 text-white hover:bg-slate-700' }}">Transaksi</a>
            @endif
            @if(Auth::user()->role === 'siswa')
                <a href="{{ route('siswa.history') }}" class="rounded-lg px-3 py-2 text-sm {{ request()->routeIs('siswa.history') ? 'bg-white text-slate-900' : 'bg-slate-800 text-white hover:bg-slate-700' }}">Riwayat Saya</a>
            @endif
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="rounded-lg bg-rose-500 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-400">Logout</button>
            </form>
        </div>
    </div>
</div>
