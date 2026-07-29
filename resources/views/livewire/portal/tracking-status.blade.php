<div class="mx-auto max-w-3xl">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('portal.pengajuan.index') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Daftar Pengajuan</span>
        </a>
        <span class="font-mono text-xs font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-lg border border-slate-200">
            {{ $pengajuan->nomor_referensi }}
        </span>
    </div>

    <!-- Status Overview Header Card -->
    <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Lacak Status Pengajuan</span>
                <h1 class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $pengajuan->jenisSurat?->nama }}</h1>
                <p class="text-xs text-slate-500 mt-1">Diajukan pada {{ $pengajuan->tanggal_pengajuan?->format('d M Y, H:i') }} WIB</p>
            </div>
            @php($warna = $pengajuan->status->color())
            <span @class([
                'inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-extrabold shadow-sm border',
                'bg-slate-100 text-slate-700 border-slate-200' => $warna === 'gray',
                'bg-blue-50 text-blue-700 border-blue-200' => $warna === 'info',
                'bg-amber-50 text-amber-700 border-amber-200' => $warna === 'warning',
                'bg-rose-50 text-rose-700 border-rose-200' => $warna === 'danger',
                'bg-emerald-50 text-emerald-700 border-emerald-200' => $warna === 'success',
            ])>
                <span @class([
                    'h-2 w-2 rounded-full',
                    'bg-slate-500' => $warna === 'gray',
                    'bg-blue-500' => $warna === 'info',
                    'bg-amber-500 animate-pulse' => $warna === 'warning',
                    'bg-rose-500' => $warna === 'danger',
                    'bg-emerald-500' => $warna === 'success',
                ])></span>
                <span>{{ $pengajuan->status->label() }}</span>
            </span>
        </div>

        {{-- Warning Box jika Perlu Revisi --}}
        @if ($pengajuan->status->value === 'direvisi' && $pengajuan->catatan_revisi)
            <div class="mt-6 rounded-2xl border border-amber-300 bg-amber-50/80 p-5 text-amber-900 shadow-sm flex flex-col sm:flex-row items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="h-8 w-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold shrink-0 mt-0.5">!</div>
                    <div>
                        <h4 class="font-bold text-sm">Permohonan Perlu Revisi</h4>
                        <p class="mt-1 text-xs leading-relaxed font-medium text-amber-800">{{ $pengajuan->catatan_revisi }}</p>
                    </div>
                </div>
                <a href="{{ route('portal.pengajuan.revisi', $pengajuan) }}" wire:navigate
                    class="shrink-0 inline-flex items-center gap-1.5 rounded-xl bg-amber-600 px-4 py-2.5 text-xs font-bold text-white shadow hover:bg-amber-700 transition">
                    <span>Perbaiki &amp; Kirim Ulang →</span>
                </a>
            </div>
        @endif

        {{-- Warning Box jika Ditolak --}}
        @if ($pengajuan->status->value === 'ditolak' && $pengajuan->alasan_penolakan)
            <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-5 text-rose-900 flex items-start gap-3">
                <div class="h-8 w-8 rounded-full bg-rose-600 text-white flex items-center justify-center font-bold shrink-0 mt-0.5">✕</div>
                <div>
                    <h4 class="font-bold text-sm">Permohonan Ditolak</h4>
                    <p class="mt-1 text-xs leading-relaxed font-medium text-rose-800">{{ $pengajuan->alasan_penolakan }}</p>
                </div>
            </div>
        @endif

        {{-- Banner Surat Terbit & TTE Download --}}
        @if ($this->tautanUnduh)
            <div class="mt-6 rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-500/10 via-teal-500/10 to-emerald-500/5 p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-md shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-sm">Surat Telah Terbit &amp; TTE Terverifikasi</h4>
                        <p class="font-mono text-xs text-emerald-800 font-bold mt-0.5">Nomor: {{ $pengajuan->suratTerbit->nomor_surat }}</p>
                    </div>
                </div>
                <a href="{{ $this->tautanUnduh }}" target="_blank"
                    class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-3 text-xs font-extrabold text-white shadow-lg shadow-emerald-950/20 hover:from-emerald-500 hover:to-teal-500 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Unduh PDF Resmi</span>
                </a>
            </div>
        @endif

        <!-- Progress Timeline Stepper -->
        <div class="mt-8">
            <h3 class="text-sm font-bold text-slate-900 mb-6">Alur Progress Approval</h3>
            <div class="relative pl-6 space-y-6 before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                @foreach ($this->timeline as $step)
                    <div class="relative flex items-center gap-4">
                        <span @class([
                            'absolute -left-6 flex h-6 w-6 items-center justify-center rounded-full text-xs font-extrabold shadow-sm ring-4 ring-white',
                            'bg-emerald-600 text-white' => $step['selesai'],
                            'bg-amber-500 text-white animate-pulse' => $step['aktif'],
                            'bg-slate-200 text-slate-400' => ! $step['selesai'] && ! $step['aktif'],
                        ])>
                            @if ($step['selesai']) ✓ @else &bull; @endif
                        </span>
                        <div class="flex-1">
                            <p @class([
                                'text-xs font-bold',
                                'text-slate-900' => $step['selesai'] || $step['aktif'],
                                'text-slate-400' => ! $step['selesai'] && ! $step['aktif'],
                            ])>{{ $step['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Approval Logs History -->
        @if ($pengajuan->approvalLogs->isNotEmpty())
            <div class="mt-10 border-t border-slate-100 pt-6">
                <h3 class="text-sm font-bold text-slate-900 mb-3">Catatan Riwayat Persetujuan</h3>
                <div class="space-y-3">
                    @foreach ($pengajuan->approvalLogs as $log)
                        <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100 text-xs">
                            <div class="flex items-center justify-between font-bold text-slate-800">
                                <span>Level {{ $log->level }} — {{ ucfirst($log->keputusan) }}</span>
                                <span class="text-[11px] font-normal text-slate-400">{{ $log->created_at?->format('d M Y, H:i') }} WIB</span>
                            </div>
                            @if ($log->catatan)
                                <p class="mt-1.5 text-slate-600 font-medium leading-relaxed">{{ $log->catatan }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
