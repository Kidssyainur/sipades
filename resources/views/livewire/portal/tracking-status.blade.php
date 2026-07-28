<div class="mx-auto max-w-2xl">
    <a href="{{ route('portal.dashboard') }}" wire:navigate class="text-sm font-medium text-emerald-600 hover:underline">← Kembali ke Dashboard</a>

    <div class="mt-4 rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $pengajuan->jenisSurat?->nama }}</h1>
                <p class="mt-1 font-mono text-xs text-gray-500">{{ $pengajuan->nomor_referensi }}</p>
            </div>
            @php($warna = $pengajuan->status->color())
            <span @class([
                'inline-flex rounded-full px-3 py-1 text-sm font-medium',
                'bg-gray-100 text-gray-700' => $warna === 'gray',
                'bg-blue-100 text-blue-700' => $warna === 'info',
                'bg-amber-100 text-amber-700' => $warna === 'warning',
                'bg-red-100 text-red-700' => $warna === 'danger',
                'bg-emerald-100 text-emerald-700' => $warna === 'success',
            ])>{{ $pengajuan->status->label() }}</span>
        </div>

        {{-- Revisi / penolakan --}}
        @if ($pengajuan->status->value === 'direvisi' && $pengajuan->catatan_revisi)
            <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                <p class="font-semibold">Perlu Revisi</p>
                <p class="mt-1">{{ $pengajuan->catatan_revisi }}</p>
                <a href="{{ route('portal.pengajuan.revisi', $pengajuan) }}" wire:navigate
                   class="mt-3 inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 font-semibold text-white hover:bg-amber-700">
                    Perbaiki &amp; Kirim Ulang
                </a>
            </div>
        @endif
        @if ($pengajuan->status->value === 'ditolak' && $pengajuan->alasan_penolakan)
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                <p class="font-semibold">Alasan Penolakan</p>
                <p class="mt-1">{{ $pengajuan->alasan_penolakan }}</p>
            </div>
        @endif

        {{-- Surat terbit --}}
        @if ($this->tautanUnduh)
            <div class="mt-4 flex items-center justify-between rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                <div class="text-sm text-emerald-800">
                    <p class="font-semibold">Surat telah terbit</p>
                    <p class="font-mono text-xs">{{ $pengajuan->suratTerbit->nomor_surat }}</p>
                </div>
                <a href="{{ $this->tautanUnduh }}" target="_blank"
                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                    Unduh PDF
                </a>
            </div>
        @endif

        {{-- Timeline --}}
        <ol class="mt-8 space-y-4">
            @foreach ($this->timeline as $step)
                <li class="flex items-start gap-3">
                    <span @class([
                        'mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold',
                        'bg-emerald-600 text-white' => $step['selesai'],
                        'bg-amber-500 text-white animate-pulse' => $step['aktif'],
                        'bg-gray-200 text-gray-400' => ! $step['selesai'] && ! $step['aktif'],
                    ])>
                        @if ($step['selesai']) ✓ @else &nbsp; @endif
                    </span>
                    <span @class([
                        'text-sm',
                        'font-semibold text-gray-900' => $step['selesai'] || $step['aktif'],
                        'text-gray-400' => ! $step['selesai'] && ! $step['aktif'],
                    ])>{{ $step['label'] }}</span>
                </li>
            @endforeach
        </ol>

        {{-- Riwayat approval --}}
        @if ($pengajuan->approvalLogs->isNotEmpty())
            <div class="mt-8">
                <h2 class="text-sm font-semibold text-gray-700">Riwayat Persetujuan</h2>
                <ul class="mt-3 space-y-3 text-sm">
                    @foreach ($pengajuan->approvalLogs as $log)
                        <li class="rounded-lg bg-gray-50 p-3">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-900">Level {{ $log->level }} — {{ ucfirst($log->keputusan) }}</span>
                                <span class="text-xs text-gray-400">{{ $log->created_at?->format('d M Y H:i') }}</span>
                            </div>
                            @if ($log->catatan)
                                <p class="mt-1 text-gray-600">{{ $log->catatan }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($pengajuan->status->value === 'direvisi')
            <div class="mt-6">
                <a href="{{ route('portal.pengajuan.buat') }}" wire:navigate
                    class="inline-block rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                    Ajukan Ulang
                </a>
            </div>
        @endif
    </div>
</div>
