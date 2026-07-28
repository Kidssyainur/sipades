<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Permission approval kustom (di luar bawaan Shield) — PRD §10.
        $permissionsKustom = ['approve_level_1', 'approve_level_2', 'approve_level_3_sign'];
        foreach ($permissionsKustom as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Roles — PRD §16.
        $roles = ['warga', 'petugas', 'sekretaris_desa', 'kepala_desa', 'admin'];
        $created = [];
        foreach ($roles as $role) {
            $created[$role] = Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Ikat permission approval sesuai matrix PRD §10.
        $created['petugas']->givePermissionTo('approve_level_1');
        $created['sekretaris_desa']->givePermissionTo('approve_level_2');
        $created['kepala_desa']->givePermissionTo('approve_level_3_sign');

        // Permission resource Filament (dari Shield) untuk role staf — PRD §12.
        // Ketiga role approver perlu melihat antrian PengajuanSurat.
        $lihatPengajuan = ['ViewAny:PengajuanSurat', 'View:PengajuanSurat'];
        foreach (['petugas', 'sekretaris_desa', 'kepala_desa'] as $role) {
            $this->giveIfExists($created[$role], $lihatPengajuan);
        }

        // Kepala Desa juga melihat rekap surat terbit.
        $this->giveIfExists($created['kepala_desa'], ['ViewAny:SuratTerbit', 'View:SuratTerbit']);

        // Admin = super_admin (bypass gate via config), namun tetap diberi seluruh
        // permission eksplisit agar konsisten bila intercept dinonaktifkan.
        $created['admin']->givePermissionTo(Permission::all());

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Berikan permission hanya jika permission-nya sudah ada (di-generate Shield).
     *
     * @param  array<int, string>  $names
     */
    private function giveIfExists(Role $role, array $names): void
    {
        foreach ($names as $name) {
            if (Permission::where('name', $name)->where('guard_name', 'web')->exists()) {
                $role->givePermissionTo($name);
            }
        }
    }
}
