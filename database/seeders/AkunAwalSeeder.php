<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AkunAwalSeeder extends Seeder
{
    public function run(): void
    {
        // Akun staf dibuat manual pasca-migrate (bukan lewat portal registrasi warga) — PRD §16.
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
        }
    }
}
