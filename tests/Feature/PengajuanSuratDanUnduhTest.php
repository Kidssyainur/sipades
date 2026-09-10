<?php

namespace Tests\Feature;

use App\Enums\StatusPengajuan;
use App\Livewire\Portal\PortalLogin;
use App\Livewire\Portal\ProfilSaya;
use App\Livewire\Portal\RegistrasiForm;
use App\Models\DataKependudukan;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\SuratTerbit;
use App\Models\User;
use App\Services\SuratPdfService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PengajuanSuratDanUnduhTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'warga']);
        Role::firstOrCreate(['name' => 'admin']);
    }

    public function test_registrasi_blocks_invalid_whatsapp_with_letters(): void
    {
        Livewire::test(RegistrasiForm::class)
            ->set('nik', '3529010101800001')
            ->set('name', 'Test Warga')
            ->set('no_hp', '0812abc345')
            ->set('email', 'test@desa.id')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('daftar')
            ->assertHasErrors(['no_hp' => 'regex']);

        Livewire::test(RegistrasiForm::class)
            ->set('no_hp', 'hurufsemua')
            ->call('daftar')
            ->assertHasErrors(['no_hp' => 'regex']);
    }

    public function test_profil_blocks_invalid_whatsapp_with_letters(): void
    {
        $user = User::factory()->create(['no_hp' => '081234567890']);
        $user->assignRole('warga');

        $this->actingAs($user);

        Livewire::test(ProfilSaya::class)
            ->set('no_hp', '0812abcde')
            ->call('simpanProfil')
            ->assertHasErrors(['no_hp' => 'regex']);
    }

    public function test_portal_login_blocks_invalid_whatsapp_with_letters(): void
    {
        Livewire::test(PortalLogin::class)
            ->set('nik', '3529010101800001')
            ->set('no_hp', '0812abcde')
            ->call('login')
            ->assertHasErrors(['no_hp' => 'regex']);
    }

    public function test_surat_pdf_generation_matches_applicant_nik_and_file_is_downloadable(): void
    {
        Storage::fake('local');

        $applicantNik = '3529010101990001';
        $warga = User::factory()->create([
            'nik' => $applicantNik,
            'name' => 'Pemohon Asli',
            'no_hp' => '081234567890',
        ]);
        $warga->assignRole('warga');

        // Dummy data kependudukan dengan NIK yang sama
        DataKependudukan::create([
            'nik' => $applicantNik,
            'nama' => 'Pemohon Asli',
            'tempat_lahir' => 'Sumenep',
            'tanggal_lahir' => '1999-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Dusun Tengah',
            'rt_rw' => '001/001',
            'agama' => 'Islam',
            'status_perkawinan' => 'Belum Kawin',
            'pekerjaan' => 'Wiraswasta',
            'kewarganegaraan' => 'WNI',
            'sudah_didaftarkan' => true,
        ]);

        $jenis = JenisSurat::create([
            'kode' => 'DOMISILI',
            'nama' => 'Surat Keterangan Domisili',
            'deskripsi' => 'Keterangan domisili warga.',
            'persyaratan' => ['KTP', 'KK'],
            'field_formulir' => [
                ['name' => 'keperluan', 'label' => 'Keperluan', 'type' => 'text', 'required' => true],
                ['name' => 'alamat_domisili', 'label' => 'Alamat Domisili', 'type' => 'textarea', 'required' => true],
            ],
            'template_view' => 'surat.domisili',
            'estimasi_hari' => 1,
            'jumlah_level_approval' => 1,
            'butuh_tte_kades' => true,
            'is_active' => true,
        ]);

        $pengajuan = PengajuanSurat::create([
            'nomor_referensi' => 'REF-20260905-9999',
            'user_id' => $warga->id,
            'jenis_surat_id' => $jenis->id,
            'data_formulir' => [
                'keperluan' => 'Membuka rekening bank',
                'alamat_domisili' => 'Dusun Timur RT 02 RW 01',
            ],
            'status' => StatusPengajuan::SELESAI,
            'current_level' => 1,
            'tanggal_pengajuan' => now(),
            'tanggal_selesai' => now(),
        ]);

        $admin = User::factory()->create(['name' => 'Kepala Desa']);
        $admin->assignRole('admin');

        $nomorSurat = '140/999/435.302.10/2026';
        $surat = SuratTerbit::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'nomor_surat' => $nomorSurat,
            'diterbitkan_oleh' => $admin->id,
            'file_path' => 'surat/pending.pdf',
            'tte_token' => 'TTE-KDL-TEST12345678',
            'tanggal_terbit' => now(),
        ]);

        $pdfService = app(SuratPdfService::class);
        $filePath = $pdfService->generate($pengajuan, $nomorSurat, $surat);
        $surat->update(['file_path' => $filePath]);

        // 1. Pastikan file PDF tersimpan di disk
        $this->assertTrue(Storage::disk('local')->exists($filePath));

        // 2. Pastikan unduh via signed route mengembalikan HTTP 200 (Bukan 404)
        $downloadUrl = URL::temporarySignedRoute('surat.unduh', now()->addMinutes(15), ['surat' => $surat->id]);
        $response = $this->get($downloadUrl);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');

        // 3. Verifikasi view HTML identitas memuat NIK warga yang mengajukan
        $html = view('surat.domisili', [
            'pengajuan' => $pengajuan,
            'penduduk' => DataKependudukan::where('nik', $applicantNik)->first(),
            'data' => $pengajuan->data_formulir,
            'nomorSurat' => $nomorSurat,
            'suratTerbit' => $surat,
            'tteToken' => $surat->tte_token,
            'verifikasiUrl' => 'http://localhost/verifikasi',
            'qrBase64' => '',
            'tanggalTerbit' => now(),
            'desa' => ['nama' => 'Desa Karduluk', 'kecamatan' => 'Pragaan', 'kabupaten' => 'Sumenep'],
        ])->render();

        $this->assertStringContainsString($applicantNik, $html);
        $this->assertStringContainsString('Pemohon Asli', $html);
    }
}
