@php
    $record = (isset($entry) && method_exists($entry, 'getRecord') ? $entry->getRecord() : null)
        ?? (isset($getRecord) && is_callable($getRecord) ? $getRecord() : null)
        ?? ($record ?? null);
    $mediaItems = $record ? $record->getMedia('lampiran') : collect();
    $persyaratan = $record?->jenisSurat?->persyaratan ?? [];
@endphp

<div class="space-y-4 text-xs">
    {{-- Dokumen Persyaratan yang Diminta --}}
    @if (! empty($persyaratan))
        <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-3.5 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-2 font-bold text-slate-700 dark:text-slate-200 mb-2">
                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Daftar Persyaratan Resmi ({{ $record->jenisSurat?->nama }}):</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-slate-600 dark:text-slate-300 font-medium pl-1">
                @foreach ($persyaratan as $syarat)
                    <li>{{ $syarat }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Daftar Berkas yang Diunggah --}}
    <div>
        <div class="font-bold text-slate-800 dark:text-slate-100 mb-2.5 flex items-center justify-between">
            <span>Berkas yang Diunggah Warga ({{ $mediaItems->count() }} file):</span>
        </div>

        @if ($mediaItems->isEmpty())
            <div class="rounded-xl border border-dashed border-amber-300 bg-amber-50/60 p-4 text-center text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300">
                <p class="font-semibold">⚠️ Tidak ada berkas lampiran yang diunggah oleh pemohon.</p>
                <p class="text-[11px] mt-0.5 text-amber-700/80 dark:text-amber-400">Pastikan berkas persyaratan diverifikasi secara manual jika diperlukan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach ($mediaItems as $media)
                    @php
                        $isImage = str_starts_with($media->mime_type, 'image/');
                        $isPdf = $media->mime_type === 'application/pdf';
                        $url = $media->getUrl();
                    @endphp

                    <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm dark:border-white/10 dark:bg-white/5 flex flex-col justify-between hover:border-emerald-400 transition-colors">
                        <div class="flex items-start gap-3">
                            {{-- Preview / Icon --}}
                            <div class="shrink-0">
                                @if ($isImage)
                                    <a href="{{ $url }}" target="_blank" title="Klik untuk memperbesar gambar">
                                        <img src="{{ $url }}" alt="{{ $media->file_name }}"
                                            class="w-14 h-14 rounded-lg object-cover border border-slate-200 dark:border-white/10 shadow-xs hover:opacity-90 transition">
                                    </a>
                                @elseif ($isPdf)
                                    <div class="w-14 h-14 rounded-lg bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400 flex items-center justify-center border border-rose-100 dark:border-rose-500/20">
                                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-14 h-14 rounded-lg bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-slate-300 flex items-center justify-center border border-slate-200">
                                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Metadata file --}}
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-slate-900 dark:text-white truncate" title="{{ $media->file_name }}">
                                    {{ $media->file_name }}
                                </p>
                                <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-500 dark:text-slate-400">
                                    <span class="font-semibold uppercase px-1.5 py-0.5 rounded-sm bg-slate-100 dark:bg-white/10 text-slate-700 dark:text-slate-300 text-[10px]">
                                        {{ $media->extension ?: ($isPdf ? 'pdf' : 'file') }}
                                    </span>
                                    <span>{{ $media->human_readable_size }}</span>
                                </div>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">
                                    Diunggah: {{ $media->created_at?->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-white/10 flex items-center justify-end gap-2">
                            <a href="{{ $url }}" target="_blank"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-white/10 dark:hover:bg-white/20 text-slate-700 dark:text-slate-200 font-bold transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>Buka File</span>
                            </a>
                            <a href="{{ $url }}" download="{{ $media->file_name }}"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-xs transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span>Unduh</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
