<?php

namespace Tests\Unit;

use App\Models\DataKependudukan;
use App\Models\User;
use App\Services\NikValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NikValidationServiceTest extends TestCase
{
    use RefreshDatabase;

    private NikValidationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new NikValidationService();
    }

    public function test_validate_returns_invalid_when_nik_not_found_in_kependudukan(): void
    {
        $result = $this->service->validate('9999999999999999');

        $this->assertFalse($result['valid']);
        $this->assertEquals('NIK tidak ditemukan pada data kependudukan desa.', $result['pesan']);
        $this->assertNull($result['data']);
    }

    public function test_validate_returns_invalid_when_nik_already_registered_in_kependudukan(): void
    {
        DataKependudukan::create([
            'nik' => '3529019999990001',
            'nama' => 'Test Resident',
            'tempat_lahir' => 'Sumenep',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Dusun Test',
            'rt_rw' => '001/001',
            'agama' => 'Islam',
            'status_perkawinan' => 'Belum Kawin',
            'pekerjaan' => 'Lainnya',
            'sudah_didaftarkan' => true,
        ]);

        $result = $this->service->validate('3529019999990001');

        $this->assertFalse($result['valid']);
        $this->assertEquals('NIK ini sudah terdaftar sebagai akun. Silakan login.', $result['pesan']);
    }

    public function test_validate_returns_valid_for_unregistered_kependudukan_nik(): void
    {
        $penduduk = DataKependudukan::create([
            'nik' => '3529019999990002',
            'nama' => 'Valid Resident',
            'tempat_lahir' => 'Sumenep',
            'tanggal_lahir' => '1995-05-05',
            'jenis_kelamin' => 'P',
            'alamat' => 'Dusun Test',
            'rt_rw' => '002/001',
            'agama' => 'Islam',
            'status_perkawinan' => 'Belum Kawin',
            'pekerjaan' => 'Lainnya',
            'sudah_didaftarkan' => false,
        ]);

        $result = $this->service->validate('3529019999990002');

        $this->assertTrue($result['valid']);
        $this->assertEquals('NIK valid.', $result['pesan']);
        $this->assertNotNull($result['data']);
        $this->assertEquals($penduduk->nik, $result['data']->nik);
    }
}
