<?php

namespace App\Livewire\Portal;

use App\Enums\StatusPengajuan;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Services\PengajuanSuratService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.portal')]
#[Title('Ajukan Surat')]
class PengajuanSuratForm extends Component
{
    use WithFileUploads;

    /** Langkah wizard: 1 = pilih jenis, 2 = isi formulir, 3 = pratinjau. */
    public int $langkah = 1;

    public ?int $jenis_surat_id = null;

    /** @var array<string, mixed> */
    public array $formulir = [];

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $lampiran = [];

    /** Bila diisi, form berjalan dalam mode kirim ulang revisi (FR-04). */
    public ?int $pengajuan_id = null;

    public ?string $catatan_revisi = null;

    private ?JenisSurat $jenisSuratCache = null;

    public function mount(?PengajuanSurat $pengajuan = null): void
    {
        if (! $pengajuan?->exists) {
            return;
        }

        // Mode revisi: hanya pemilik & hanya status direvisi yang boleh mengirim ulang.
        abort_unless($pengajuan->user_id === Auth::id(), 403);
        abort_unless($pengajuan->status === StatusPengajuan::DIREVISI, 403);

        $this->pengajuan_id = $pengajuan->id;
        $this->jenis_surat_id = $pengajuan->jenis_surat_id;
        $this->formulir = $pengajuan->data_formulir ?? [];
        $this->catatan_revisi = $pengajuan->catatan_revisi;
        $this->langkah = 2;
    }

    public function getJenisSuratProperty(): ?JenisSurat
    {
        if (! $this->jenis_surat_id) {
            return null;
        }

        return $this->jenisSuratCache ??= JenisSurat::find($this->jenis_surat_id);
    }

    public function pilihJenis(int $id): void
    {
        $this->jenis_surat_id = $id;
        $this->jenisSuratCache = null;
        $this->formulir = [];
        $this->langkah = 2;
    }

    public function keFormulir(): void
    {
        if (! $this->getJenisSuratProperty()) {
            $this->addError('jenis_surat_id', 'Silakan pilih jenis surat.');

            return;
        }

        $this->langkah = 2;
    }

    public function kePratinjau(): void
    {
        $this->validate($this->aturanValidasi(), [], $this->labelValidasi());
        $this->langkah = 3;
    }

    public function kembali(): void
    {
        if ($this->langkah > 1) {
            $this->langkah--;
        }
    }

    public function submit(PengajuanSuratService $service)
    {
        $this->validate($this->aturanValidasi(), [], $this->labelValidasi());

        $jenis = $this->getJenisSuratProperty();

        $files = array_values(array_filter($this->lampiran));

        // Mode kirim ulang revisi (FR-04).
        if ($this->pengajuan_id) {
            $pengajuan = PengajuanSurat::findOrFail($this->pengajuan_id);
            abort_unless($pengajuan->user_id === Auth::id(), 403);

            $service->ajukanUlang(
                pengajuan: $pengajuan,
                dataFormulir: $this->formulir,
                lampiran: array_map(fn ($f) => $f, $files),
            );

            session()->flash('status', "Pengajuan {$pengajuan->nomor_referensi} berhasil dikirim ulang dan akan diverifikasi kembali.");

            return $this->redirectRoute('portal.pengajuan.status', $pengajuan->id, navigate: true);
        }

        $pengajuan = $service->ajukan(
            warga: Auth::user(),
            jenisSurat: $jenis,
            dataFormulir: $this->formulir,
            lampiran: array_map(fn ($f) => $f, $files),
        );

        session()->flash('status', "Pengajuan berhasil dikirim. Nomor referensi: {$pengajuan->nomor_referensi}");

        return $this->redirectRoute('portal.pengajuan.status', $pengajuan->id, navigate: true);
    }

    /**
     * @return array<string, mixed>
     */
    private function aturanValidasi(): array
    {
        $jenis = $this->getJenisSuratProperty();
        $rules = [];

        foreach ($jenis?->field_formulir ?? [] as $field) {
            $key = 'formulir.'.$field['name'];
            $r = ($field['required'] ?? false) ? ['required'] : ['nullable'];

            $r[] = match ($field['type'] ?? 'text') {
                'number' => 'numeric',
                'date' => 'date',
                default => 'string',
            };

            $rules[$key] = $r;
        }

        // Lampiran: maks 2MB per file, tipe pdf/jpg/png — §11.3 poin 2.
        $rules['lampiran.*'] = ['file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'];

        // Wajib upload minimal satu lampiran bila jenis surat punya persyaratan.
        // Pada mode revisi lampiran lama tetap dipakai, jadi unggahan baru opsional.
        if (! empty($jenis?->persyaratan) && ! $this->pengajuan_id) {
            $rules['lampiran'] = ['required', 'array', 'min:1'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    private function labelValidasi(): array
    {
        $labels = [
            'lampiran.required' => 'Harap unggah dokumen persyaratan.',
            'lampiran.*.mimes' => 'Lampiran harus berupa file PDF, JPG, atau PNG.',
            'lampiran.*.max' => 'Ukuran setiap lampiran maksimal 2MB.',
        ];

        foreach ($this->getJenisSuratProperty()?->field_formulir ?? [] as $field) {
            $labels['formulir.'.$field['name'].'.required'] = 'Kolom "'.$field['label'].'" wajib diisi.';
        }

        return $labels;
    }

    public function render()
    {
        return view('livewire.portal.pengajuan-surat-form', [
            'daftarJenis' => JenisSurat::where('is_active', true)->orderBy('nama')->get(),
        ]);
    }
}
