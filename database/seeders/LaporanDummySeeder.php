<?php

namespace Database\Seeders;

use App\Enums\StatusPengajuan;
use App\Models\DataKependudukan;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\SuratTerbit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class LaporanDummySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat beberapa akun warga contoh
        $penduduk = DataKependudukan::all();
        $wargaRole = Role::firstOrCreate(['name' => 'warga', 'guard_name' => 'web']);
        $wargaUsers = [];

        $wargaUtama = User::where('email', 'warga@karduluk.desa.id')->first();
        if ($wargaUtama) {
            $wargaUsers[] = $wargaUtama;
        }

        foreach ($penduduk as $data) {
            // Biarkan 3 NIK ini TIDAK dibuatkan akun user agar selalu siap diuji di Form Registrasi Warga
            if (in_array($data->nik, ['3529011506980006', '3529016012020007', '3529012504990008'])) {
                continue;
            }

            $user = User::where('nik', $data->nik)->first();
            if (! $user) {
                $user = User::create([
                    'name' => $data->nama,
                    'nik' => $data->nik,
                    'email' => strtolower(str_replace(' ', '', $data->nama)) . '@gmail.com',
                    'no_hp' => '62852' . rand(1000000, 9999999),
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
                $user->syncRoles([$wargaRole]);
            }
            $wargaUsers[] = $user;
        }

        $adminUser = User::where('email', 'admin@karduluk.desa.id')->first();
        $jenisSurats = JenisSurat::all();

        if ($jenisSurats->isEmpty() || empty($wargaUsers)) {
            return;
        }

        // 2. Pastikan Warga Utama (warga@karduluk.desa.id) Memiliki Pengajuan Khusus untuk Testing
        if ($wargaUtama) {
            $jenisContoh = $jenisSurats->first();
            
            // Pengajuan 1: DIREVISI (agar /portal/pengajuan/{id}/revisi bisa diakses 200 OK)
            $pRevisi = PengajuanSurat::create([
                'nomor_referensi' => 'REG-' . now()->format('Ymd') . '-0001',
                'user_id' => $wargaUtama->id,
                'jenis_surat_id' => $jenisContoh->id,
                'data_formulir' => [
                    'keperluan' => 'Keperluan Surat Keterangan Usaha (Revisi Dokumen)',
                    'alamat_domisili' => 'Dusun Tengah RT 01 RW 02 Desa Karduluk',
                    'nama_usaha' => 'Toko Kelontong Berkah',
                ],
                'status' => StatusPengajuan::DIREVISI->value,
                'current_level' => 1,
                'catatan_revisi' => 'Mohon unggah kembali Kartu Keluarga terbaru yang terlegalisir.',
                'tanggal_pengajuan' => now()->subDays(2),
            ]);

            // Pengajuan 2: SELESAI & Surat Terbit (agar /portal/pengajuan/{id}/status & unduh bisa diakses 200 OK)
            $pSelesai = PengajuanSurat::create([
                'nomor_referensi' => 'REG-' . now()->format('Ymd') . '-0002',
                'user_id' => $wargaUtama->id,
                'jenis_surat_id' => $jenisContoh->id,
                'data_formulir' => [
                    'keperluan' => 'Keperluan Administrasi Bank BCA',
                    'alamat_domisili' => 'Dusun Tengah RT 01 RW 02 Desa Karduluk',
                ],
                'status' => StatusPengajuan::SELESAI->value,
                'current_level' => 3,
                'tanggal_pengajuan' => now()->subDays(5),
                'tanggal_selesai' => now()->subDays(4),
            ]);

            $nomorDummy = '140/001/435.302.10/' . date('Y');
            $tteToken = 'TTE-KDL-DEMO12345';

            $surat = SuratTerbit::create([
                'pengajuan_surat_id' => $pSelesai->id,
                'nomor_surat' => $nomorDummy,
                'diterbitkan_oleh' => $adminUser?->id ?? 1,
                'file_path' => 'surat/pending.pdf',
                'tte_token' => $tteToken,
                'tanggal_terbit' => $pSelesai->tanggal_selesai,
            ]);

            $filePath = app(\App\Services\SuratPdfService::class)->generate($pSelesai, $nomorDummy, $surat);
            $surat->update(['file_path' => $filePath]);
        }

        $statuses = [
            StatusPengajuan::SELESAI->value,
            StatusPengajuan::SELESAI->value,
            StatusPengajuan::SELESAI->value,
            StatusPengajuan::DIAJUKAN->value,
            StatusPengajuan::DIVERIFIKASI_PETUGAS->value,
            StatusPengajuan::DISETUJUI_SEKRETARIS->value,
            StatusPengajuan::DISETUJUI_KEPALA->value,
            StatusPengajuan::DIREVISI->value,
            StatusPengajuan::DITOLAK->value,
        ];

        // 3. Generate 40 pengajuan surat contoh dalam kurun 6 bulan terakhir
        $counter = 3;
        for ($monthOffset = 5; $monthOffset >= 0; $monthOffset--) {
            $baseDate = Carbon::now()->subMonths($monthOffset)->startOfMonth();

            $countThisMonth = rand(5, 8);
            for ($i = 0; $i < $countThisMonth; $i++) {
                $user = $wargaUsers[array_rand($wargaUsers)];
                $jenis = $jenisSurats->random();
                $status = $statuses[array_rand($statuses)];

                $tglPengajuan = (clone $baseDate)->addDays(rand(1, 26))->addHours(rand(8, 16));
                $nomorRef = 'REG-' . $tglPengajuan->format('Ymd') . '-' . str_pad((string) $counter, 4, '0', STR_PAD_LEFT);

                $pengajuan = PengajuanSurat::create([
                    'nomor_referensi' => $nomorRef,
                    'user_id' => $user->id,
                    'jenis_surat_id' => $jenis->id,
                    'data_formulir' => [
                        'keperluan' => 'Keperluan ' . $jenis->nama . ' untuk administrasi warga',
                        'alamat_domisili' => 'Dusun Tengah RT 01 RW 02 Desa Karduluk',
                        'nama_usaha' => 'Toko Kelontong Berkah',
                    ],
                    'status' => $status,
                    'current_level' => match ($status) {
                        StatusPengajuan::DIAJUKAN->value => 1,
                        StatusPengajuan::DIVERIFIKASI_PETUGAS->value => 2,
                        StatusPengajuan::DISETUJUI_SEKRETARIS->value => 3,
                        default => 3,
                    },
                    'catatan_revisi' => $status === StatusPengajuan::DIREVISI->value ? 'Mohon lengkapi lampiran Kartu Keluarga' : null,
                    'alasan_penolakan' => $status === StatusPengajuan::DITOLAK->value ? 'Data NIK dan Alamat tidak sesuai dengan berkas fisik' : null,
                    'tanggal_pengajuan' => $tglPengajuan,
                    'tanggal_selesai' => $status === StatusPengajuan::SELESAI->value ? (clone $tglPengajuan)->addDays(rand(1, 3)) : null,
                ]);

                if ($status === StatusPengajuan::SELESAI->value) {
                    $nomorDummy = '140/' . str_pad((string) $counter, 3, '0', STR_PAD_LEFT) . '/435.302.10/' . $tglPengajuan->format('Y');
                    $tteToken = 'TTE-KDL-' . strtoupper(\Illuminate\Support\Str::random(16));

                    $surat = SuratTerbit::create([
                        'pengajuan_surat_id' => $pengajuan->id,
                        'nomor_surat' => $nomorDummy,
                        'diterbitkan_oleh' => $adminUser?->id ?? 1,
                        'file_path' => 'surat/pending.pdf',
                        'tte_token' => $tteToken,
                        'tanggal_terbit' => $pengajuan->tanggal_selesai,
                    ]);

                    $filePath = app(\App\Services\SuratPdfService::class)->generate($pengajuan, $nomorDummy, $surat);
                    $surat->update(['file_path' => $filePath]);
                }

                $counter++;
            }
        }
    }
}
