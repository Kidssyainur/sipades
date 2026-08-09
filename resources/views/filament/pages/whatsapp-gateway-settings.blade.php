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
                <!-- Session Controls Toolbar using Native Filament Actions -->
                <div class="flex flex-wrap items-center gap-3">
                    {{ $this->startSessionAction }}

                    @if($qr)
                        {{ $this->showQrModalAction }}
                    @endif

                    @if(isset($statusKoneksi['status']) && $statusKoneksi['status'] === 'UNREACHABLE')
                        {{ $this->startSidecarAction }}
                    @endif

                    {{ $this->stopSessionAction }}

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

    <!-- Render Native Filament Modals -->
    <x-filament-actions::modals />
</x-filament-panels::page>
