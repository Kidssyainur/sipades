<x-filament-panels::page>
    <div wire:poll.3s="cekStatusKoneksi" class="space-y-6" x-data="{ openModal: @entangle('showQrModal') }">
        <!-- Live Connection Status & Session Manager Card -->
        <x-filament::section icon="heroicon-o-chat-bubble-left-right">
            <x-slot name="heading">
                Status Live WhatsApp Web Sidecar
            </x-slot>
            <x-slot name="description">
                Backend: whatsapp-web.js (Node.js Sidecar) &bull; Session ID: <code class="font-mono text-primary-600 font-bold bg-primary-50 dark:bg-primary-950 px-2 py-0.5 rounded">{{ $sessionId }}</code>
            </x-slot>
            <x-slot name="headerEnd">
                <div class="flex items-center gap-2">
                    @if($statusKoneksi)
                        @php
                            $statusStr = strtolower($statusKoneksi['status'] ?? 'unknown');
                        @endphp

                        @if($statusStr === 'ready')
                            <x-filament::badge color="success" icon="heroicon-o-check-circle">
                                ONLINE / TERHUBUNG (READY)
                            </x-filament::badge>
                        @elseif($statusStr === 'qr')
                            <x-filament::badge color="warning" icon="heroicon-o-qr-code">
                                BUTUH SCAN QR CODE
                            </x-filament::badge>
                        @elseif($statusStr === 'initializing' || $statusStr === 'authenticated')
                            <x-filament::badge color="info" icon="heroicon-o-arrow-path">
                                {{ strtoupper($statusStr) }}...
                            </x-filament::badge>
                        @else
                            <x-filament::badge color="danger" icon="heroicon-o-x-circle">
                                {{ strtoupper($statusKoneksi['status'] ?? 'DISCONNECTED') }}
                            </x-filament::badge>
                        @endif
                    @endif

                    <x-filament::button wire:click="cekStatusKoneksi" size="xs" color="gray" icon="heroicon-o-arrow-path">
                        Cek Status
                    </x-filament::button>
                </div>
            </x-slot>

            <div class="space-y-6 pt-2">
                <!-- Session Controls Toolbar -->
                <div class="flex flex-wrap items-center gap-3">
                    <x-filament::button wire:click="startSession" color="success" icon="heroicon-o-qr-code">
                        Start / Pairing QR
                    </x-filament::button>

                    @if($qr)
                        <x-filament::button wire:click="openQrModal" color="warning" icon="heroicon-o-qr-code">
                            Lihat QR Code
                        </x-filament::button>
                    @endif

                    @if(isset($statusKoneksi['status']) && $statusKoneksi['status'] === 'UNREACHABLE')
                        <x-filament::button wire:click="startSidecarNodeProcess" color="info" icon="heroicon-o-play">
                            Jalankan Sidecar Node.js
                        </x-filament::button>
                    @endif

                    <x-filament::button wire:click="stopSession" color="gray" icon="heroicon-o-pause">
                        Stop Sesi
                    </x-filament::button>

                    <x-filament::button wire:click="destroySession" color="danger" icon="heroicon-o-trash"
                        wire:confirm="Apakah Anda yakin ingin me-reset (hapus) sesi WhatsApp? Perangkat terhubung akan di-logout dan QR Code baru akan dibuat untuk scan ulang.">
                        Reset Pairing (Hapus Sesi)
                    </x-filament::button>
                </div>

                <!-- Context Info Notice Callout -->
                <div>
                    @if(isset($statusKoneksi['online']) && $statusKoneksi['online'])
                        <div class="p-4 rounded-xl border border-success-300 bg-success-50/50 dark:border-success-800 dark:bg-success-950/30 flex items-start gap-3">
                            <x-heroicon-o-check-circle class="h-6 w-6 text-success-600 dark:text-success-400 shrink-0" style="width: 1.5rem; height: 1.5rem;" />
                            <div class="text-sm text-success-900 dark:text-success-200 leading-relaxed">
                                <strong class="font-bold block mb-1">WhatsApp Terhubung &amp; Ready!</strong>
                                Perangkat WhatsApp Anda sudah aktif dan siap mengirim notifikasi. Jika Anda ingin menghubungkan nomor HP lain atau memperbarui scan QR Code, silakan klik tombol <span class="font-semibold underline">Reset Pairing (Hapus Sesi)</span> di atas.
                            </div>
                        </div>
                    @elseif($qr)
                        <div class="p-4 rounded-xl border border-warning-300 bg-warning-50/50 dark:border-warning-800 dark:bg-warning-950/30 flex items-start gap-3">
                            <x-heroicon-o-qr-code class="h-6 w-6 text-warning-600 dark:text-warning-400 shrink-0" style="width: 1.5rem; height: 1.5rem;" />
                            <div class="text-sm text-warning-900 dark:text-warning-200 leading-relaxed">
                                <strong class="font-bold block mb-1">Menunggu Scan QR Code</strong>
                                Silakan klik tombol <span class="font-semibold underline">Lihat QR Code</span> di atas atau tunggu modal QR Code tampil untuk melakukan pairing dengan WhatsApp smartphone Anda.
                            </div>
                        </div>
                    @elseif(isset($statusKoneksi['pesan']))
                        <div class="p-3 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs font-mono text-gray-700 dark:text-gray-300">
                            Keterangan: {{ $statusKoneksi['pesan'] }}
                        </div>
                    @endif
                </div>
            </div>
        </x-filament::section>

        <!-- Direct Test Message Form Section -->
        <div style="margin-top: 1.5rem;" class="mt-6">
            <x-filament::section icon="heroicon-o-paper-airplane">
                <x-slot name="heading">
                    Tes Pengiriman Pesan WhatsApp Direct
                </x-slot>
                <x-slot name="description">
                    Kirim pesan simulasi langsung melalui laravel-whatsapp Web Sidecar untuk memverifikasi koneksi.
                </x-slot>

                <form wire:submit="kirimTestPesan" class="space-y-6">
                    {{ $this->form }}

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800" style="margin-top: 1.5rem; padding-top: 1rem;">
                        <x-filament::button 
                            type="submit" 
                            icon="heroicon-o-paper-airplane" 
                            size="md" 
                            color="success"
                            wire:loading.attr="disabled"
                            wire:target="kirimTestPesan"
                        >
                            Kirim Pesan Test
                        </x-filament::button>
                    </div>
                </form>
            </x-filament::section>
        </div>

        <!-- Modal QR Code Pairing -->
        <template x-teleport="body">
            <div 
                x-show="openModal" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
                style="display: none;"
            >
                <div 
                    @click.away="openModal = false"
                    x-show="openModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-800 p-6 text-center overflow-hidden"
                >
                    <!-- Close button -->
                    <button 
                        @click="openModal = false; $wire.closeQrModal()" 
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-lg transition-colors"
                    >
                        <x-heroicon-o-x-mark class="h-6 w-6" style="width: 1.5rem; height: 1.5rem;" />
                    </button>

                    <div class="mb-4 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-success-50 dark:bg-success-950/50 text-success-700 dark:text-success-300 text-xs font-bold border border-success-200 dark:border-success-800">
                        <span class="h-2 w-2 rounded-full bg-success-500 animate-ping"></span>
                        Auto-close saat berhasil terhubung
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                        Pairing Perangkat WhatsApp
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-5">
                        Arahkan kamera WhatsApp smartphone Anda ke QR Code di bawah
                    </p>

                    @if($qr)
                        <div class="p-4 bg-white rounded-2xl shadow-md border border-gray-200 inline-block mb-4">
                            <img src="{{ $qr }}" alt="WhatsApp QR Code" class="w-64 h-64 mx-auto object-contain rounded-lg" style="width: 250px; height: 250px;" />
                        </div>
                    @else
                        <div class="p-12 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-200 dark:border-gray-700 mb-4 flex flex-col items-center justify-center">
                            <svg class="animate-spin h-8 w-8 text-primary-600 dark:text-primary-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400">Memuat QR Code...</span>
                        </div>
                    @endif

                    <div class="text-left text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/60 p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 space-y-1.5">
                        <div class="font-bold text-gray-900 dark:text-white flex items-center gap-1 mb-1">
                            <x-heroicon-o-device-phone-mobile class="h-4 w-4 text-primary-600" style="width: 1rem; height: 1rem;" />
                            Langkah Pairing:
                        </div>
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Buka <strong>WhatsApp</strong> di HP Anda.</li>
                            <li>Pilih <strong>Setelan / Titik 3</strong> &rarr; <strong>Perangkat Tertaut</strong>.</li>
                            <li>Tap <strong>Tautkan Perangkat</strong> &amp; scan QR Code di atas.</li>
                        </ol>
                    </div>

                    <div class="mt-5 flex justify-end">
                        <x-filament::button color="gray" size="sm" @click="openModal = false; $wire.closeQrModal()">
                            Tutup Modal
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-filament-panels::page>
