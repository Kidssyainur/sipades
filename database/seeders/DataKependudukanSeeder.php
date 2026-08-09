<?php

namespace Database\Seeders;

use App\Models\DataKependudukan;
use Illuminate\Database\Seeder;

class DataKependudukanSeeder extends Seeder
{
    public function run(): void
    {
        // Data kependudukan contoh Desa Karduluk untuk menguji validasi NIK saat registrasi warga (FR-01).
        $penduduk = [
            [
                'nik' => '3529010101800001',
                'nama' => 'Ahmad Fauzi',
                'tempat_lahir' => 'Sumenep',
                'tanggal_lahir' => '1980-01-01',
                'jenis_kelamin' => 'L',
                'alamat' => 'Dusun Tengah',
                'rt_rw' => '001/001',
                'agama' => 'Islam',
                'status_perkawinan' => 'Kawin',
                'pekerjaan' => 'Petani',
            ],
            [
                'nik' => '3529014205850002',
                'nama' => 'Siti Aminah',
                'tempat_lahir' => 'Sumenep',
                'tanggal_lahir' => '1985-02-02',
                'jenis_kelamin' => 'P',
                'alamat' => 'Dusun Laok',
                'rt_rw' => '002/001',
                'agama' => 'Islam',
                'status_perkawinan' => 'Kawin',
                'pekerjaan' => 'Ibu Rumah Tangga',
            ],
            [
                'nik' => '3529010503920003',
                'nama' => 'Budi Santoso',
                'tempat_lahir' => 'Sumenep',
                'tanggal_lahir' => '1992-03-05',
                'jenis_kelamin' => 'L',
                'alamat' => 'Dusun Daja',
                'rt_rw' => '003/002',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'pekerjaan' => 'Wiraswasta',
            ],
            [
                'nik' => '3529015010950004',
                'nama' => 'Dewi Lestari',
                'tempat_lahir' => 'Sumenep',
                'tanggal_lahir' => '1995-10-10',
                'jenis_kelamin' => 'P',
                'alamat' => 'Dusun Barat',
                'rt_rw' => '001/002',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'pekerjaan' => 'Guru',
            ],
            [
                'nik' => '3529012208010005',
                'nama' => 'Rizky Pratama',
                'tempat_lahir' => 'Sumenep',
                'tanggal_lahir' => '2001-08-22',
                'jenis_kelamin' => 'L',
                'alamat' => 'Dusun Tengah',
                'rt_rw' => '002/002',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'pekerjaan' => 'Pelajar/Mahasiswa',
            ],
            [
                'nik' => '3529011506980006',
                'nama' => 'Mohammad Ridwan',
                'tempat_lahir' => 'Sumenep',
                'tanggal_lahir' => '1998-06-15',
                'jenis_kelamin' => 'L',
                'alamat' => 'Dusun Tengah',
                'rt_rw' => '003/001',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'pekerjaan' => 'Wiraswasta',
            ],
            [
                'nik' => '3529016012020007',
                'nama' => 'Nurul Hidayah',
                'tempat_lahir' => 'Sumenep',
                'tanggal_lahir' => '2002-12-20',
                'jenis_kelamin' => 'P',
                'alamat' => 'Dusun Laok',
                'rt_rw' => '001/003',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'pekerjaan' => 'Pelajar/Mahasiswa',
            ],
            [
                'nik' => '3529012504990008',
                'nama' => 'Faisol Anam',
                'tempat_lahir' => 'Sumenep',
                'tanggal_lahir' => '1999-04-25',
                'jenis_kelamin' => 'L',
                'alamat' => 'Dusun Barat',
                'rt_rw' => '002/003',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'pekerjaan' => 'Petani',
            ],
        ];

        foreach ($penduduk as $data) {
            DataKependudukan::updateOrCreate(
                ['nik' => $data['nik']],
                $data + ['kewarganegaraan' => 'WNI', 'sudah_didaftarkan' => false],
            );
        }
    }
}
