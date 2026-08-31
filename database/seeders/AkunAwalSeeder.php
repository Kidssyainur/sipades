<?php

namespace Database\Seeders;

use App\Models\DataKependudukan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AkunAwalSeeder extends Seeder
{
    public function run(): void
    {
        // Akun awal staf & warga uji coba — PRD §16.
        $akun = [
            [
                'name' => 'Administrator',
                'email' => 'admin@karduluk.desa.id',
                'no_hp' => '6281200000001',
                'role' => 'admin',
            ],
            [
                'name' => 'Kepala Desa Karduluk',
                'email' => 'kepaladesa@karduluk.desa.id',
                'no_hp' => '6281200000002',
                'role' => 'kepala_desa',
            ],
            [
                'name' => 'Sekretaris Desa Karduluk',
                'email' => 'sekretaris@karduluk.desa.id',
                'no_hp' => '6281200000003',
                'role' => 'sekretaris_desa',
            ],
            [
                'name' => 'Petugas Desa',
                'email' => 'petugas@karduluk.desa.id',
                'no_hp' => '6281200000004',
                'role' => 'petugas',
            ],
            [
                'name' => 'Ahmad Fauzi (Warga Uji Coba)',
                'email' => 'warga@karduluk.desa.id',
                'nik' => '3529010101800001',
                'no_hp' => '6281234567890',
                'role' => 'warga',
            ],
        ];

        foreach ($akun as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data + [
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ],
            );

            $user->syncRoles([$role]);

            if ($role === 'warga' && ! empty($user->nik)) {
                DataKependudukan::where('nik', (string) $user->nik)->update([
                    'sudah_didaftarkan' => true,
                ]);
            }
        }
    }
}
