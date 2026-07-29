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

        foreach ($penduduk as $data) {
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

        // 2. Generate 45 pengajuan surat contoh dalam kurun 6 bulan terakhir (Februari - Juli 2026)
        $counter = 1;
        for ($monthOffset = 5; $monthOffset >= 0; $monthOffset--) {
            $baseDate = Carbon::now()->subMonths($monthOffset)->startOfMonth();

            // Buat 7-8 pengajuan per bulan
            $countThisMonth = rand(6, 9);
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
