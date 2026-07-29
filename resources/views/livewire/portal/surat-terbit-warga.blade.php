<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Arsip Surat Terbit</h1>
            <p class="mt-1 text-sm text-gray-500">Daftar seluruh surat resmi milik Anda yang telah disetujui & terbit dengan TTE.</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mt-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor surat resmi / jenis surat..."
            class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
    </div>

    <!-- Data Table / Grid -->
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        @forelse ($suratList as $surat)
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-emerald-100 flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                            ✓ TTE Resmi Terbit
                        </span>
                        <span class="text-xs text-gray-400 font-mono">
                            {{ $surat->tanggal_terbit?->format('d M Y') }}
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900">{{ $surat->pengajuanSurat?->jenisSurat?->nama }}</h3>
                    <p class="mt-1 font-mono text-xs font-semibold text-emerald-700">No: {{ $surat->nomor_surat }}</p>

                    @if(!empty($surat->tte_token))
                        <div class="mt-3 bg-gray-50 p-2.5 rounded-lg border border-gray-100 text-xs font-mono text-gray-600">
                            <span class="text-[10px] text-gray-400 block uppercase">TTE Token:</span>
                            <span class="truncate block text-emerald-900 font-semibold">{{ $surat->tte_token }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex items-center justify-between gap-3 pt-3 border-t border-gray-100">
                    <a href="{{ route('portal.pengajuan.status', $surat->pengajuan_surat_id) }}"
                        class="text-xs font-semibold text-gray-600 hover:text-gray-900">
                        Detail Pengajuan
                    </a>
                    
                    @php
                        $downloadUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                            'surat.unduh',
                            now()->addDays(7),
                            ['surat' => $surat->id]
                        );
                    @endphp
                    <a href="{{ $downloadUrl }}" target="_blank"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-emerald-500 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Unduh PDF (Signed)
                    </a>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 rounded-2xl bg-white p-12 text-center text-gray-400 shadow-sm border border-gray-100">
                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Belum ada surat terbit. Surat yang telah disetujui akan muncul di sini.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $suratList->links() }}
    </div>
</div>
