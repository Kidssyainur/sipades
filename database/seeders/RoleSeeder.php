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

        // 1. Permission approval kustom (di luar bawaan Shield) — PRD §10.
        $permissionsKustom = [
            'approve_level_1',
            'approve_level_2',
            'approve_level_3_sign',
        ];

        // 2. Permission standar Resource & Pages Filament
        $resources = ['PengajuanSurat', 'JenisSurat', 'User', 'SuratTerbit', 'TemplatePesan', 'NotifikasiLog', 'Activity'];
        $actions = ['ViewAny', 'View', 'Create', 'Update', 'Delete'];
        
        $resourcePermissions = [];
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $resourcePermissions[] = "{$action}:{$resource}";
            }
        }

        $pagePermissions = ['View:Laporan', 'View:WhatsappGatewaySettings'];

        $allPermissions = array_merge($permissionsKustom, $resourcePermissions, $pagePermissions);

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 3. Roles — PRD §16.
        $roles = ['warga', 'petugas', 'sekretaris_desa', 'kepala_desa', 'admin'];
        $created = [];
        foreach ($roles as $role) {
            $created[$role] = Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // 4. Ikat permission approval & akses resource sesuai matrix PRD §10.
        $created['petugas']->givePermissionTo(['approve_level_1', 'ViewAny:PengajuanSurat', 'View:PengajuanSurat']);
        
        $created['sekretaris_desa']->givePermissionTo(['approve_level_2', 'ViewAny:PengajuanSurat', 'View:PengajuanSurat']);
        
        $created['kepala_desa']->givePermissionTo([
            'approve_level_3_sign',
            'ViewAny:PengajuanSurat',
            'View:PengajuanSurat',
            'ViewAny:SuratTerbit',
            'View:SuratTerbit',
            'View:Laporan',
        ]);

        // 5. Admin = Beri seluruh permission
        $created['admin']->givePermissionTo(Permission::all());

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
