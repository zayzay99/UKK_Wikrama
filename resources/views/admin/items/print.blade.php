<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode - {{ $item->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 p-6 text-slate-900">
    <div class="mx-auto max-w-2xl">
        <div class="no-print mb-6 flex items-center justify-between">
            <a href="{{ route('items.index') }}" class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-300">Kembali</a>
            <button onclick="window.print()" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Print Barcode</button>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow">
            <div class="mb-6 border-b border-dashed border-slate-300 pb-4 text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Label Barang</p>
                <h1 class="mt-2 text-2xl font-bold">{{ $item->name }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ strtoupper($item->type) }} | Stok {{ $item->stock }}</p>
            </div>

            <div class="flex justify-center">
                <div class="rounded-xl border border-slate-200 bg-white p-5 text-center">
                    <img
                        src="https://bwipjs-api.metafloor.com/?bcid=code128&text={{ urlencode($item->code) }}&scale=4&height=18&includetext"
                        alt="Barcode {{ $item->code }}"
                        class="mx-auto h-40 w-full max-w-xl object-contain"
                    >
                    <p class="mt-4 text-xl font-bold tracking-[0.2em]">{{ $item->code }}</p>
                    <p class="mt-2 text-sm text-slate-500">Scan barcode ini saat proses peminjaman barang.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            if (window.location.search.includes('autoprint=1')) {
                window.print();
            }
        });
    </script>
</body>
</html>
