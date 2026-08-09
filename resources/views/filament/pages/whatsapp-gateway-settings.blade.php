<x-filament-panels::page>
    <div wire:poll.3s="cekStatusKoneksi" class="space-y-6">
        <!-- Live Connection Status & Session Manager Card -->
        <x-filament::section icon="heroicon-o-chat-bubble-left-right">
            <x-slot name="heading">
                Status Live WhatsApp Web Sidecar
            </x-slot>
            <x-slot name="description">
                Backend: whatsapp-web.js (Node.js Sidecar) &bull; Session ID: <code class="font-mono text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-950 px-2 py-0.5 rounded">{{ $sessionId }}</code>
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
                        <x-filament::button wire:click="openQrModal" x-on:click="$dispatch('open-modal', { id: 'qr-pairing-modal' })" color="warning" icon="heroicon-o-qr-code">
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

                    {{ $this->destroySessionAction }}
                </div>

                <!-- Context Info Notice Callout -->
                <div>
                    @if(isset($statusKoneksi['online']) && $statusKoneksi['online'])
                        <div class="p-4 rounded-xl border border-emerald-300 bg-emerald-50/50 dark:border-emerald-800 dark:bg-emerald-950/30 flex items-start gap-3">
                            <x-heroicon-o-check-circle class="h-6 w-6 text-emerald-600 dark:text-emerald-400 shrink-0" style="width: 1.5rem; height: 1.5rem;" />
                            <div class="text-sm text-emerald-900 dark:text-emerald-200 leading-relaxed">
                                <strong class="font-bold block mb-1">WhatsApp Terhubung &amp; Ready!</strong>
                                Perangkat WhatsApp Anda sudah aktif dan siap mengirim notifikasi. Jika Anda ingin menghubungkan nomor HP lain atau memperbarui scan QR Code, silakan klik tombol <span class="font-semibold underline">Reset Pairing (Hapus Sesi)</span> di atas.
                            </div>
                        </div>
                    @elseif($qr)
                        <div class="p-4 rounded-xl border border-amber-300 bg-amber-50/50 dark:border-amber-800 dark:bg-amber-950/30 flex items-start gap-3">
                            <x-heroicon-o-qr-code class="h-6 w-6 text-amber-600 dark:text-amber-400 shrink-0" style="width: 1.5rem; height: 1.5rem;" />
                            <div class="text-sm text-amber-900 dark:text-amber-200 leading-relaxed">
                                <strong class="font-bold block mb-1">Menunggu Scan QR Code</strong>
                                Silakan klik tombol <span class="font-semibold underline">Lihat QR Code</span> di atas untuk membuka modal pairing QR Code.
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
    </div>

    <!-- Native Filament Modal Component for Floating Centered QR Code Pop-up -->
    <x-filament::modal
        id="qr-pairing-modal"
        width="md"
        alignment="center"
        icon="heroicon-o-qr-code"
        icon-color="warning"
    >
        <x-slot name="heading">
            Pairing Perangkat WhatsApp
        </x-slot>

        <x-slot name="description">
            Arahkan kamera aplikasi WhatsApp di smartphone Anda ke QR Code di bawah.
        </x-slot>

        <div style="padding-top: 0.5rem; text-align: center;">
            <div style="margin-bottom: 1rem;">
                <x-filament::badge color="success" icon="heroicon-o-arrow-path">
                    Auto-close saat berhasil terhubung
                </x-filament::badge>
            </div>

            @if($qr)
                <div style="background-color: #ffffff; padding: 1rem; border-radius: 1rem; border: 1px solid #e5e7eb; display: inline-block; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); margin-bottom: 1rem;">
                    <img src="{{ $qr }}" alt="WhatsApp QR Code" style="width: 250px; height: 250px; margin: 0 auto; display: block; border-radius: 0.5rem;" />
                </div>
            @else
                <div style="padding: 2rem 1rem; text-align: center; border: 2px dashed #cbd5e1; border-radius: 1rem; margin-bottom: 1rem;" class="dark:border-gray-700">
                    <x-heroicon-o-arrow-path class="h-8 w-8 text-amber-500 animate-spin mx-auto mb-2" style="width: 2rem; height: 2rem;" />
                    <span style="font-size: 0.875rem; font-weight: 600;" class="text-gray-600 dark:text-gray-400">Memuat QR Code dari Sidecar Node.js...</span>
                </div>
            @endif

            <div style="text-align: left; background-color: rgba(243, 244, 246, 0.8); border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 0.875rem 1rem;" class="dark:bg-gray-800/80 dark:border-gray-700">
                <div style="font-weight: 700; color: #10b981; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.375rem; font-size: 0.875rem;" class="dark:text-emerald-400">
                    <x-heroicon-o-device-phone-mobile class="h-4 w-4" style="width: 1rem; height: 1rem;" />
                    Langkah-langkah Pairing:
                </div>
                <ol style="list-style-type: decimal; padding-left: 1.25rem; margin: 0; line-height: 1.6; font-size: 0.8125rem;" class="text-gray-700 dark:text-gray-300">
                    <li>Buka aplikasi <strong>WhatsApp</strong> di HP Anda.</li>
                    <li>Pilih <strong>Setelan / Titik 3</strong> &rarr; <strong>Perangkat Tertaut</strong>.</li>
                    <li>Tap <strong>Tautkan Perangkat</strong> &amp; arahkan kamera ke QR Code di atas.</li>
                </ol>
            </div>
        </div>

        <x-slot name="footer">
            <div style="display: flex; justify-content: flex-end;">
                <x-filament::button color="gray" size="sm" wire:click="closeQrModal" x-on:click="$dispatch('close-modal', { id: 'qr-pairing-modal' })">
                    Tutup Modal
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::modal>

    <!-- Render Filament Actions Modals (Confirmation dialogs) -->
    <x-filament-actions::modals />
</x-filament-panels::page>
