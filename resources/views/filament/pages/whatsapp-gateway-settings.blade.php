<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Live Connection Status & Parameter Info Card -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Status Live Go-WA Gateway</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Penyedia Layanan WhatsApp Multidevice REST API (aldinokemal/go-whatsapp-web-multidevice)</p>
                </div>
                <div class="flex items-center gap-3">
                    @if($statusKoneksi)
                        @if($statusKoneksi['online'])
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                ONLINE / TERHUBUNG
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-800 dark:bg-red-950 dark:text-red-300">
                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                {{ $statusKoneksi['status'] ?? 'DISCONNECTED' }}
                            </span>
                        @endif
                    @endif

                    <x-filament::button wire:click="cekStatusKoneksi" size="xs" color="gray" icon="heroicon-o-arrow-path">
                        Cek Status Live
                    </x-filament::button>
                </div>
            </div>

            <!-- Parameters Overview Grid -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">SERVER BASE URL</span>
                    <p class="mt-1 truncate text-xs font-mono font-bold text-emerald-700 dark:text-emerald-400">
                        {{ config('services.gowa.url') }}
                    </p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">DEVICE ID</span>
                    <p class="mt-1 truncate text-xs font-mono font-semibold text-gray-900 dark:text-white">
                        {{ config('services.gowa.device_id') }}
                    </p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">HTTP BASIC USER</span>
                    <p class="mt-1 text-xs font-mono font-semibold text-gray-900 dark:text-white">
                        {{ config('services.gowa.username') }}
                    </p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">REQUEST TIMEOUT</span>
                    <p class="mt-1 text-xs font-mono font-semibold text-gray-900 dark:text-white">
                        {{ config('services.gowa.timeout') }} Detik
                    </p>
                </div>
            </div>

            @if($statusKoneksi && !empty($statusKoneksi['pesan']))
                <div class="mt-4 rounded-lg bg-gray-100 p-3 text-xs font-mono text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    Keterangan: {{ $statusKoneksi['pesan'] }}
                </div>
            @endif
        </div>

        <!-- Direct Test Message Form -->
        <form wire:submit="kirimTestPesan" class="space-y-6">
            {{ $this->form }}

            <x-filament::button type="submit" icon="heroicon-o-paper-airplane" size="lg" color="emerald">
                Kirim Pesan Pengujian (Go-WA)
            </x-filament::button>
        </form>
    </div>
</x-filament-panels::page>
