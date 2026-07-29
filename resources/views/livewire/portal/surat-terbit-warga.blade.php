<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Arsip Surat Terbit</h1>
            <p class="mt-1 text-xs text-slate-500">Daftar seluruh surat resmi milik Anda yang telah disetujui & terbit dengan TTE terverifikasi.</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mt-6 bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor surat resmi / jenis surat..."
                class="w-full rounded-xl border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2.5 text-xs focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all" />
        </div>
    </div>

    <!-- Data Grid -->
    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
        @forelse ($suratList as $surat)
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-xl hover:border-emerald-200 transition-all duration-300 group">
                <div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-extrabold text-emerald-700 shadow-sm">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            <span>✓ TTE Resmi Terbit</span>
                        </span>
                        <span class="text-xs text-slate-400 font-medium">
                            Terbit: {{ $surat->tanggal_terbit?->format('d M Y') }}
                        </span>
                    </div>

                    <h3 class="text-lg font-extrabold text-slate-900 group-hover:text-emerald-600 transition-colors">
                        {{ $surat->pengajuanSurat?->jenisSurat?->nama }}
                    </h3>
                    <p class="mt-1 font-mono text-xs font-bold text-slate-700">No: {{ $surat->nomor_surat }}</p>

                    @if(!empty($surat->tte_token))
                        <div class="mt-4 bg-slate-50 p-3 rounded-2xl border border-slate-100 text-xs font-mono">
                            <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">TTE Verification Token:</span>
                            <span class="truncate block text-emerald-800 font-bold mt-0.5">{{ $surat->tte_token }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex items-center justify-between gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('portal.pengajuan.status', $surat->pengajuan_surat_id) }}"
                        class="text-xs font-bold text-slate-500 hover:text-slate-900 transition">
                        Lacak Status →
                    </a>
                    
                    @php
                        $downloadUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                            'surat.unduh',
                            now()->addDays(7),
                            ['surat' => $surat->id]
                        );
                    @endphp
                    <a href="{{ $downloadUrl }}" target="_blank"
                        class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-950/20 hover:from-emerald-500 hover:to-teal-500 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Unduh PDF (Signed)</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 rounded-3xl bg-white p-12 text-center shadow-sm border border-slate-100">
                <div class="h-16 w-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-sm font-bold text-slate-700">Belum Ada Surat Terbit</p>
                <p class="text-xs text-slate-400 mt-1">Surat permohonan yang telah disetujui Kepala Desa akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $suratList->links() }}
    </div>
</div>
