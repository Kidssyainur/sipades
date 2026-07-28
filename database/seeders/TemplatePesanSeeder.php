<?php

namespace Database\Seeders;

use App\Models\TemplatePesan;
use Illuminate\Database\Seeder;

class TemplatePesanSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'kode' => 'PENGAJUAN_DITERIMA',
                'judul' => 'Pengajuan Diterima',
                'isi_template' => "Halo {nama}, pengajuan {jenis_surat} Anda dengan nomor referensi {nomor_referensi} telah kami terima dan sedang diproses. Terima kasih.",
            ],
            [
                'kode' => 'REVISI_DIMINTA',
                'judul' => 'Revisi Diminta',
                'isi_template' => "Halo {nama}, pengajuan {jenis_surat} ({nomor_referensi}) memerlukan revisi. Catatan: {catatan}. Silakan perbaiki melalui portal.",
            ],
            [
                'kode' => 'DITOLAK',
                'judul' => 'Pengajuan Ditolak',
                'isi_template' => "Halo {nama}, mohon maaf pengajuan {jenis_surat} ({nomor_referensi}) ditolak. Alasan: {alasan}.",
            ],
            [
                'kode' => 'DISETUJUI_PETUGAS',
                'judul' => 'Disetujui Petugas',
                'isi_template' => "Halo {nama}, pengajuan {jenis_surat} ({nomor_referensi}) telah diverifikasi petugas dan diteruskan ke Sekretaris Desa.",
            ],
            [
                'kode' => 'DISETUJUI_SEKRETARIS',
                'judul' => 'Disetujui Sekretaris',
                'isi_template' => "Halo {nama}, pengajuan {jenis_surat} ({nomor_referensi}) telah disetujui Sekretaris Desa dan menunggu tanda tangan Kepala Desa.",
            ],
            [
                'kode' => 'SURAT_TERBIT',
                'judul' => 'Surat Terbit',
                'isi_template' => "Halo {nama}, {jenis_surat} Anda telah terbit dengan nomor {nomor_surat}. Unduh di sini (berlaku 7 hari): {tautan}",
            ],
        ];

        foreach ($templates as $template) {
            TemplatePesan::updateOrCreate(
                ['kode' => $template['kode']],
                $template + ['is_active' => true],
            );
        }
    }
}
