<div class="mx-auto max-w-3xl">
    <!-- Stepper Progress Bar -->
    <div class="mb-8 rounded-2xl bg-white p-4 shadow-sm border border-slate-100">
        <ol class="flex items-center justify-between text-xs sm:text-sm font-bold">
            @foreach (['Pilih Jenis Surat', 'Isi Formulir & Lampiran', 'Pratinjau & Kirim'] as $i => $judul)
                @php($no = $i + 1)
                <li class="flex flex-1 items-center gap-2.5">
                    <span @class([
                        'flex h-9 w-9 items-center justify-center rounded-xl text-xs font-extrabold shadow-sm transition-all duration-300',
                        'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-emerald-900/20' => $langkah >= $no,
                        'bg-slate-100 text-slate-400' => $langkah < $no,
                    ])>
                        @if ($langkah > $no)
                            ✓
                        @else
                            {{ $no }}
                        @endif
                    </span>
                    <span @class([
                        'font-bold transition-colors',
                        'text-slate-900' => $langkah >= $no,
                        'text-slate-400' => $langkah < $no
                    ])>{{ $judul }}</span>
                    @if (! $loop->last)
                        <span class="mx-2 hidden flex-1 border-t-2 border-dashed border-slate-200 sm:block"></span>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>

    <!-- Main Wizard Card Container -->
    <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
        {{-- LANGKAH 1: Pilih Jenis Surat --}}
        @if ($langkah === 1)
            <div class="border-b border-slate-100 pb-4 mb-6">
                <h1 class="text-2xl font-extrabold text-slate-900">Pilih Jenis Surat</h1>
                <p class="text-xs text-slate-500 mt-1">Silakan pilih jenis surat permohonan yang ingin Anda ajukan.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach ($daftarJenis as $jenis)
                    <button type="button" wire:click="pilihJenis({{ $jenis->id }})"
                        @class([
                            'group relative rounded-2xl border-2 p-5 text-left transition-all duration-200 hover:-translate-y-0.5',
                            'border-emerald-500 bg-emerald-50/50 shadow-md ring-2 ring-emerald-500/20' => $jenis_surat_id === $jenis->id,
                            'border-slate-200/80 hover:border-emerald-400 hover:bg-slate-50' => $jenis_surat_id !== $jenis->id,
                        ])>
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">{{ $jenis->nama }}</h3>
                            <span class="inline-flex shrink-0 items-center rounded-lg bg-emerald-100 px-2 py-0.5 text-[11px] font-bold text-emerald-800">
                                {{ $jenis->estimasi_hari }} Hari
                            </span>
                        </div>
                        @if ($jenis->deskripsi)
                            <p class="mt-2 text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $jenis->deskripsi }}</p>
                        @endif

                        @if (! empty($jenis->persyaratan))
                            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-semibold text-slate-400">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ count($jenis->persyaratan) }} Dokumen Syarat</span>
                            </div>
                        @endif
                    </button>
                @endforeach
            </div>
            @error('jenis_surat_id') <p class="mt-4 text-xs font-bold text-rose-600 bg-rose-50 p-3 rounded-xl border border-rose-200">{{ $message }}</p> @enderror
        @endif

        {{-- LANGKAH 2: Formulir Dinamis + Lampiran --}}
        @if ($langkah === 2 && $this->jenisSurat)
            <div class="border-b border-slate-100 pb-4 mb-6 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Formulir Permohonan</span>
                    <h1 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $this->jenisSurat->nama }}</h1>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                    Estimasi {{ $this->jenisSurat->estimasi_hari }} Hari Kerja
                </span>
            </div>

            @if ($pengajuan_id && $catatan_revisi)
                <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-500/10 p-4 text-xs text-amber-900 flex items-start gap-3">
                    <span class="text-base">⚠️</span>
                    <div>
                        <p class="font-bold">Catatan Revisi dari Petugas:</p>
                        <p class="mt-1 font-medium leading-relaxed">{{ $catatan_revisi }}</p>
                        <p class="mt-2 text-[11px] text-amber-700 font-semibold">Perbaiki data di bawah lalu klik tombol "Pratinjau". Lampiran lama tetap tersimpan bila Anda tidak mengunggah berkas baru.</p>
                    </div>
                </div>
            @endif

            <form wire:submit="kePratinjau" class="space-y-5">
                @foreach ($this->jenisSurat->field_formulir ?? [] as $field)
                    @php($name = 'formulir.'.$field['name'])
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            {{ $field['label'] }}
                            @if ($field['required'] ?? false) <span class="text-rose-500">*</span> @endif
                        </label>

                        @if (($field['type'] ?? 'text') === 'textarea')
                            <textarea wire:model="{{ $name }}" rows="3" placeholder="Masukkan {{ strtolower($field['label']) }}..."
                                class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-sm focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all"></textarea>
                        @elseif (($field['type'] ?? '') === 'select')
                            <select wire:model="{{ $name }}"
                                class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-sm focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all">
                                <option value="">— Pilih {{ $field['label'] }} —</option>
                                @foreach ($field['options'] ?? [] as $opsi)
                                    <option value="{{ $opsi }}">{{ $opsi }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="{{ $field['type'] ?? 'text' }}" wire:model="{{ $name }}" placeholder="Masukkan {{ strtolower($field['label']) }}..."
                                class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-sm focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all">
                        @endif

                        @error($name) <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
                    </div>
                @endforeach

                {{-- Dokumen Persyaratan & Upload --}}
                @if (! empty($this->jenisSurat->persyaratan))
                    <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                        <p class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Dokumen Persyaratan Wajib Diketahui:</span>
                        </p>
                        <ul class="mt-2 space-y-1 text-xs text-slate-600 font-medium pl-6 list-disc">
                            @foreach ($this->jenisSurat->persyaratan as $syarat)
                                <li>{{ $syarat }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="rounded-2xl border-2 border-dashed border-slate-200 p-6 text-center hover:border-emerald-400 transition-colors">
                    <label class="cursor-pointer">
                        <div class="mx-auto h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 block">Klik atau Seret Berkas Lampiran ke Sini</span>
                        <span class="text-[11px] text-slate-400 mt-1 block">Format PDF, JPG, atau PNG (Maksimal 2MB per berkas)</span>
                        <input type="file" wire:model="lampiran" multiple accept=".pdf,.jpg,.jpeg,.png" class="sr-only">
                    </label>

                    <div wire:loading wire:target="lampiran" class="mt-2 text-xs font-bold text-emerald-600">Mengunggah berkas...</div>
                    @error('lampiran') <p class="mt-2 text-xs font-bold text-rose-600 bg-rose-50 p-2 rounded-lg">{{ $message }}</p> @enderror

                    @if (! empty($lampiran))
                        <div class="mt-4 flex flex-wrap gap-2 justify-center">
                            @foreach ($lampiran as $file)
                                @if ($file)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-xl text-xs font-semibold text-emerald-800">
                                        ✓ {{ $file->getClientOriginalName() }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <button type="button" wire:click="kembali" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">← Kembali</button>
                    <button type="submit"
                        class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-3 text-xs font-bold text-white shadow-lg shadow-emerald-950/20 hover:from-emerald-400 hover:to-teal-400 transition-all">
                        Pratinjau Data →
                    </button>
                </div>
            </form>
        @endif

        {{-- LANGKAH 3: Pratinjau & Konfirmasi --}}
        @if ($langkah === 3 && $this->jenisSurat)
            <div class="border-b border-slate-100 pb-4 mb-6">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Konfirmasi Pengajuan</span>
                <h1 class="text-2xl font-extrabold text-slate-900 mt-0.5">Pratinjau Permohonan Surat</h1>
                <p class="text-xs text-slate-500 mt-1">Periksa kembali data yang telah Anda isi sebelum dikirimkan ke pihak desa.</p>
            </div>

            <div class="rounded-2xl bg-slate-50/80 p-6 border border-slate-200/80 space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-slate-200/60">
                    <span class="text-xs font-bold text-slate-500">Jenis Surat</span>
                    <span class="text-sm font-extrabold text-slate-900">{{ $this->jenisSurat->nama }}</span>
                </div>
                @foreach ($this->jenisSurat->field_formulir ?? [] as $field)
                    <div class="flex justify-between items-center py-1">
                        <span class="text-xs font-medium text-slate-500">{{ $field['label'] }}</span>
                        <span class="text-xs font-bold text-slate-800 text-right">{{ $formulir[$field['name']] ?? '—' }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between items-center pt-3 border-t border-slate-200/60">
                    <span class="text-xs font-bold text-slate-500">Lampiran Diunggah</span>
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-lg">
                        {{ count(array_filter($lampiran)) }} Berkas Berhasil Diunggah
                    </span>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-between">
                <button type="button" wire:click="kembali" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">← Ubah Data</button>
                <button type="button" wire:click="submit" wire:loading.attr="disabled" wire:target="submit"
                    class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-xs font-bold text-white shadow-xl shadow-emerald-900/30 hover:from-emerald-400 hover:to-teal-400 transition-all disabled:opacity-60">
                    <span wire:loading.remove wire:target="submit">Kirim Pengajuan Sekarang →</span>
                    <span wire:loading wire:target="submit">Memproses &amp; Mengirim...</span>
                </button>
            </div>
        @endif
    </div>
</div>
