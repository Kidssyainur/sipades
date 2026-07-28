<div class="mx-auto max-w-2xl">
    {{-- Indikator langkah --}}
    <ol class="mb-8 flex items-center justify-between text-sm">
        @foreach (['Pilih Jenis', 'Isi Formulir', 'Pratinjau'] as $i => $judul)
            @php($no = $i + 1)
            <li class="flex flex-1 items-center gap-2">
                <span @class([
                    'flex h-8 w-8 items-center justify-center rounded-full font-semibold',
                    'bg-emerald-600 text-white' => $langkah >= $no,
                    'bg-gray-200 text-gray-500' => $langkah < $no,
                ])>{{ $no }}</span>
                <span @class(['font-medium' => $langkah >= $no, 'text-gray-400' => $langkah < $no])>{{ $judul }}</span>
                @if (! $loop->last) <span class="mx-2 hidden flex-1 border-t border-gray-200 sm:block"></span> @endif
            </li>
        @endforeach
    </ol>

    <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
        {{-- LANGKAH 1: pilih jenis surat --}}
        @if ($langkah === 1)
            <h1 class="text-xl font-bold text-gray-900">Pilih Jenis Surat</h1>
            <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                @foreach ($daftarJenis as $jenis)
                    <button type="button" wire:click="pilihJenis({{ $jenis->id }})"
                        @class([
                            'rounded-xl border p-4 text-left transition hover:border-emerald-400 hover:bg-emerald-50',
                            'border-emerald-500 bg-emerald-50' => $jenis_surat_id === $jenis->id,
                            'border-gray-200' => $jenis_surat_id !== $jenis->id,
                        ])>
                        <p class="font-semibold text-gray-900">{{ $jenis->nama }}</p>
                        @if ($jenis->deskripsi)
                            <p class="mt-1 text-xs text-gray-500">{{ $jenis->deskripsi }}</p>
                        @endif
                        <p class="mt-2 text-xs text-emerald-600">Estimasi {{ $jenis->estimasi_hari }} hari kerja</p>
                    </button>
                @endforeach
            </div>
            @error('jenis_surat_id') <p class="mt-3 text-sm text-red-600">{{ $message }}</p> @enderror
        @endif

        {{-- LANGKAH 2: formulir dinamis + lampiran --}}
        @if ($langkah === 2 && $this->jenisSurat)
            <h1 class="text-xl font-bold text-gray-900">{{ $this->jenisSurat->nama }}</h1>
            <p class="mt-1 text-sm text-gray-500">Lengkapi data berikut.</p>

            @if ($pengajuan_id && $catatan_revisi)
                <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                    <p class="font-semibold">Catatan revisi dari petugas</p>
                    <p class="mt-1">{{ $catatan_revisi }}</p>
                    <p class="mt-2 text-xs text-amber-600">Perbaiki data di bawah lalu kirim ulang. Lampiran lama tetap dipakai bila Anda tidak mengunggah yang baru.</p>
                </div>
            @endif

            <form wire:submit="kePratinjau" class="mt-6 space-y-4">
                @foreach ($this->jenisSurat->field_formulir ?? [] as $field)
                    @php($name = 'formulir.'.$field['name'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            {{ $field['label'] }}
                            @if ($field['required'] ?? false) <span class="text-red-500">*</span> @endif
                        </label>

                        @if (($field['type'] ?? 'text') === 'textarea')
                            <textarea wire:model="{{ $name }}" rows="3"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                        @elseif (($field['type'] ?? '') === 'select')
                            <select wire:model="{{ $name }}"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">— Pilih —</option>
                                @foreach ($field['options'] ?? [] as $opsi)
                                    <option value="{{ $opsi }}">{{ $opsi }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="{{ $field['type'] ?? 'text' }}" wire:model="{{ $name }}"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @endif

                        @error($name) <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                @endforeach

                {{-- Persyaratan & lampiran --}}
                @if (! empty($this->jenisSurat->persyaratan))
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-sm font-medium text-gray-700">Dokumen Persyaratan:</p>
                        <ul class="mt-1 list-inside list-disc text-sm text-gray-500">
                            @foreach ($this->jenisSurat->persyaratan as $syarat)
                                <li>{{ $syarat }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Unggah Lampiran (PDF/JPG/PNG, maks 2MB per file)
                    </label>
                    <input type="file" wire:model="lampiran" multiple accept=".pdf,.jpg,.jpeg,.png"
                        class="mt-1 block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-emerald-700 hover:file:bg-emerald-100">
                    <div wire:loading wire:target="lampiran" class="mt-1 text-xs text-gray-400">Mengunggah…</div>
                    @error('lampiran') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('lampiran.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                    @if (! empty($lampiran))
                        <ul class="mt-2 space-y-1 text-sm text-gray-600">
                            @foreach ($lampiran as $file)
                                @if ($file)
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-600">✓</span> {{ $file->getClientOriginalName() }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="button" wire:click="kembali" class="text-sm font-medium text-gray-500 hover:underline">← Kembali</button>
                    <button type="submit"
                        class="rounded-lg bg-emerald-600 px-5 py-2.5 font-semibold text-white hover:bg-emerald-500">
                        Pratinjau →
                    </button>
                </div>
            </form>
        @endif

        {{-- LANGKAH 3: pratinjau & konfirmasi --}}
        @if ($langkah === 3 && $this->jenisSurat)
            <h1 class="text-xl font-bold text-gray-900">Pratinjau Pengajuan</h1>
            <p class="mt-1 text-sm text-gray-500">Periksa kembali data Anda sebelum mengirim.</p>

            <dl class="mt-6 divide-y divide-gray-100 text-sm">
                <div class="flex justify-between py-2">
                    <dt class="text-gray-500">Jenis Surat</dt>
                    <dd class="font-medium text-gray-900">{{ $this->jenisSurat->nama }}</dd>
                </div>
                @foreach ($this->jenisSurat->field_formulir ?? [] as $field)
                    <div class="flex justify-between py-2">
                        <dt class="text-gray-500">{{ $field['label'] }}</dt>
                        <dd class="text-right font-medium text-gray-900">{{ $formulir[$field['name']] ?? '—' }}</dd>
                    </div>
                @endforeach
                <div class="flex justify-between py-2">
                    <dt class="text-gray-500">Lampiran</dt>
                    <dd class="text-right font-medium text-gray-900">
                        {{ count(array_filter($lampiran)) }} berkas
                    </dd>
                </div>
            </dl>

            <div class="mt-6 flex items-center justify-between">
                <button type="button" wire:click="kembali" class="text-sm font-medium text-gray-500 hover:underline">← Ubah</button>
                <button type="button" wire:click="submit" wire:loading.attr="disabled" wire:target="submit"
                    class="rounded-lg bg-emerald-600 px-5 py-2.5 font-semibold text-white hover:bg-emerald-500 disabled:opacity-60">
                    <span wire:loading.remove wire:target="submit">Kirim Pengajuan</span>
                    <span wire:loading wire:target="submit">Mengirim…</span>
                </button>
            </div>
        @endif
    </div>
</div>
