<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        $jenis = [
            [
                'kode' => 'DOMISILI',
                'nama' => 'Surat Keterangan Domisili',
                'deskripsi' => 'Keterangan tempat tinggal warga di wilayah desa.',
                'persyaratan' => ['Fotokopi KTP', 'Fotokopi Kartu Keluarga'],
                'field_formulir' => [
                    ['name' => 'keperluan', 'label' => 'Keperluan', 'type' => 'text', 'required' => true],
                    ['name' => 'alamat_domisili', 'label' => 'Alamat Domisili Saat Ini', 'type' => 'textarea', 'required' => true],
                ],
                'template_view' => 'surat.domisili',
                'estimasi_hari' => 2,
            ],
            [
                'kode' => 'USAHA',
                'nama' => 'Surat Keterangan Usaha',
                'deskripsi' => 'Keterangan kepemilikan usaha warga.',
                'persyaratan' => ['Fotokopi KTP', 'Fotokopi Kartu Keluarga', 'Foto lokasi usaha'],
                'field_formulir' => [
                    ['name' => 'nama_usaha', 'label' => 'Nama Usaha', 'type' => 'text', 'required' => true],
                    ['name' => 'jenis_usaha', 'label' => 'Jenis Usaha', 'type' => 'text', 'required' => true],
                    ['name' => 'alamat_usaha', 'label' => 'Alamat Usaha', 'type' => 'textarea', 'required' => true],
                    ['name' => 'tahun_berdiri', 'label' => 'Tahun Berdiri', 'type' => 'number', 'required' => false],
                ],
                'template_view' => 'surat.usaha',
                'estimasi_hari' => 3,
            ],
            [
                'kode' => 'SKTM',
                'nama' => 'Surat Keterangan Tidak Mampu',
                'deskripsi' => 'Keterangan kondisi ekonomi tidak mampu.',
                'persyaratan' => ['Fotokopi KTP', 'Fotokopi Kartu Keluarga', 'Surat pengantar RT/RW'],
                'field_formulir' => [
                    ['name' => 'keperluan', 'label' => 'Keperluan', 'type' => 'text', 'required' => true],
                    ['name' => 'penghasilan', 'label' => 'Penghasilan Perbulan (Rp)', 'type' => 'number', 'required' => false],
                ],
                'template_view' => 'surat.sktm',
                'estimasi_hari' => 2,
            ],
            [
                'kode' => 'PENGANTAR_KTP',
                'nama' => 'Surat Pengantar KTP',
                'deskripsi' => 'Pengantar pembuatan/perubahan KTP ke Dukcapil.',
                'persyaratan' => ['Fotokopi Kartu Keluarga', 'Pas foto'],
                'field_formulir' => [
                    ['name' => 'jenis_permohonan', 'label' => 'Jenis Permohonan', 'type' => 'select', 'options' => ['Baru', 'Perpanjangan', 'Perubahan Data', 'Hilang/Rusak'], 'required' => true],
                ],
                'template_view' => 'surat.pengantar_ktp',
                'estimasi_hari' => 1,
            ],
            [
                'kode' => 'PENGANTAR_KK',
                'nama' => 'Surat Pengantar KK',
                'deskripsi' => 'Pengantar pembuatan/perubahan Kartu Keluarga ke Dukcapil.',
                'persyaratan' => ['Fotokopi KTP', 'Fotokopi KK lama (jika ada)'],
                'field_formulir' => [
                    ['name' => 'jenis_permohonan', 'label' => 'Jenis Permohonan', 'type' => 'select', 'options' => ['Baru', 'Perubahan Data', 'Penambahan Anggota', 'Hilang/Rusak'], 'required' => true],
                ],
                'template_view' => 'surat.pengantar_kk',
                'estimasi_hari' => 1,
            ],
            [
                'kode' => 'KELAHIRAN',
                'nama' => 'Surat Keterangan Kelahiran',
                'deskripsi' => 'Keterangan kelahiran untuk pengurusan akta.',
                'persyaratan' => ['Fotokopi KTP orang tua', 'Fotokopi Kartu Keluarga', 'Surat keterangan lahir dari bidan/RS'],
                'field_formulir' => [
                    ['name' => 'nama_anak', 'label' => 'Nama Anak', 'type' => 'text', 'required' => true],
                    ['name' => 'tempat_lahir', 'label' => 'Tempat Lahir', 'type' => 'text', 'required' => true],
                    ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date', 'required' => true],
                    ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'options' => ['Laki-laki', 'Perempuan'], 'required' => true],
                    ['name' => 'nama_ayah', 'label' => 'Nama Ayah', 'type' => 'text', 'required' => true],
                    ['name' => 'nama_ibu', 'label' => 'Nama Ibu', 'type' => 'text', 'required' => true],
                ],
                'template_view' => 'surat.kelahiran',
                'estimasi_hari' => 2,
            ],
            [
                'kode' => 'KEMATIAN',
                'nama' => 'Surat Keterangan Kematian',
                'deskripsi' => 'Keterangan kematian untuk pengurusan akta.',
                'persyaratan' => ['Fotokopi KTP almarhum/ah', 'Fotokopi Kartu Keluarga', 'Surat keterangan kematian dari RS (jika ada)'],
                'field_formulir' => [
                    ['name' => 'nama_almarhum', 'label' => 'Nama Almarhum/ah', 'type' => 'text', 'required' => true],
                    ['name' => 'tanggal_meninggal', 'label' => 'Tanggal Meninggal', 'type' => 'date', 'required' => true],
                    ['name' => 'tempat_meninggal', 'label' => 'Tempat Meninggal', 'type' => 'text', 'required' => true],
                    ['name' => 'sebab_meninggal', 'label' => 'Sebab Meninggal', 'type' => 'text', 'required' => false],
                ],
                'template_view' => 'surat.kematian',
                'estimasi_hari' => 2,
            ],
        ];

        foreach ($jenis as $item) {
            JenisSurat::updateOrCreate(
                ['kode' => $item['kode']],
                $item + ['is_active' => true],
            );
        }
    }
}
