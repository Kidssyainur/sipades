<x-filament-panels::page>
    <div wire:poll.5s="cekStatusKoneksi" class="space-y-6">
        <!-- Live Connection Status & Session Manager -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 dark:border-gray-800 pb-4 mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Status Live WhatsApp Web Sidecar</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Integrasi WhatsApp Web via kstmostofa/laravel-whatsapp (Session ID: <code class="font-mono text-emerald-600 font-bold">{{ $sessionId }}</code>)</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @if($statusKoneksi)
                        @if($statusKoneksi['online'])
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                ONLINE / READY
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                {{ $statusKoneksi['status'] ?? 'DISCONNECTED' }}
                            </span>
                        @endif
                    @endif

                    <x-filament::button wire:click="cekStatusKoneksi" size="xs" color="gray" icon="heroicon-o-arrow-path">
                        Cek Status
                    </x-filament::button>
                </div>
            </div>

            <!-- Session Controls -->
            <div class="flex flex-wrap items-center gap-3">
                <x-filament::button wire:click="startSession" color="emerald" icon="heroicon-o-qr-code">
                    Start / Pairing QR
                </x-filament::button>

                <x-filament::button wire:click="stopSession" color="gray" icon="heroicon-o-pause">
                    Stop Sesi
                </x-filament::button>

                <x-filament::button wire:click="destroySession" color="danger" icon="heroicon-o-trash"
                    wire:confirm="Apakah Anda yakin ingin menghapus sesi? Perangkat harus di-pairing ulang via QR code.">
                    Destroy (Hapus Sesi)
                </x-filament::button>
            </div>

            <!-- QR Code Pairing Container -->
            @if($qr)
                <div class="mt-6 rounded-xl border border-emerald-300 bg-emerald-50/50 p-6 dark:border-emerald-800 dark:bg-emerald-950/30 flex flex-col items-center text-center">
                    <h3 class="text-sm font-bold text-emerald-900 dark:text-emerald-300 mb-2">Scan QR Code Pairing WhatsApp</h3>
                    <div class="p-3 bg-white rounded-2xl shadow-md border border-gray-200 inline-block">
                        <img src="{{ $qr }}" alt="WhatsApp QR Code" class="w-64 h-64 mx-auto" />
                    </div>
                    <p class="mt-3 text-xs text-emerald-700 dark:text-emerald-400 max-w-md">
                        Buka aplikasi WhatsApp di HP Anda &rarr; <strong>Setelan</strong> &rarr; <strong>Perangkat Tertaut</strong> &rarr; <strong>Tautkan Perangkat</strong>, lalu arahkan kamera ke QR Code di atas.
                    </p>
                </div>
            @endif

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
                Kirim Pesan Pengujian (laravel-whatsapp)
            </x-filament::button>
        </form>
    </div>
</x-filament-panels::page>
