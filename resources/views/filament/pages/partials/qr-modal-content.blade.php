<div class="py-2 text-center space-y-4">
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 text-xs font-bold border border-emerald-200 dark:border-emerald-800">
        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
        Auto-close saat berhasil terhubung
    </div>

    @if($qr)
        <div class="p-4 bg-white rounded-2xl shadow border border-gray-200 inline-block my-2">
            <img src="{{ $qr }}" alt="WhatsApp QR Code" class="w-64 h-64 mx-auto object-contain rounded-lg" style="width: 250px; height: 250px;" />
        </div>
    @else
        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
            <svg class="animate-spin h-8 w-8 text-emerald-600 dark:text-emerald-400 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Memuat QR Code...</span>
        </div>
    @endif

    <div class="text-left text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/60 p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 space-y-1">
        <div class="font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-1">
            <x-heroicon-o-device-phone-mobile class="h-4 w-4 text-emerald-600" style="width: 1rem; height: 1rem;" />
            Langkah-langkah Pairing:
        </div>
        <ol class="list-decimal list-inside space-y-1">
            <li>Buka aplikasi <strong>WhatsApp</strong> di HP Anda.</li>
            <li>Pilih <strong>Setelan / Titik 3</strong> &rarr; <strong>Perangkat Tertaut</strong>.</li>
            <li>Tap <strong>Tautkan Perangkat</strong> &amp; arahkan kamera ke QR Code di atas.</li>
        </ol>
    </div>
</div>
