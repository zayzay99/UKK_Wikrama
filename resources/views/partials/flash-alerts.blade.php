@if(session('success') || session('error') || $errors->any())
    <div id="flash-alert-container" class="mb-4 space-y-3">
        @if(session('success'))
            <div class="flash-alert flex items-start justify-between rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-green-700 shadow-sm">
                <div>
                    <p class="font-semibold">Berhasil</p>
                    <p>{{ session('success') }}</p>
                </div>
                <button type="button" class="ml-4 text-lg leading-none text-green-700 hover:text-green-900" data-close-alert>&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="flash-alert flex items-start justify-between rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-red-700 shadow-sm">
                <div>
                    <p class="font-semibold">Gagal</p>
                    <p>{{ session('error') }}</p>
                </div>
                <button type="button" class="ml-4 text-lg leading-none text-red-700 hover:text-red-900" data-close-alert>&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="flash-alert flex items-start justify-between rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-amber-800 shadow-sm">
                <div>
                    <p class="font-semibold">Periksa input</p>
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="ml-4 text-lg leading-none text-amber-800 hover:text-amber-900" data-close-alert>&times;</button>
            </div>
        @endif
    </div>

    <script>
        (function () {
            const alerts = document.querySelectorAll('#flash-alert-container .flash-alert');
            if (!alerts.length) return;

            document.querySelectorAll('#flash-alert-container [data-close-alert]').forEach((button) => {
                button.addEventListener('click', () => {
                    const alert = button.closest('.flash-alert');
                    if (alert) alert.remove();
                });
            });

            setTimeout(() => {
                alerts.forEach((alert) => {
                    alert.style.transition = 'opacity 300ms ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 320);
                });
            }, 5000);
        })();
    </script>
@endif
