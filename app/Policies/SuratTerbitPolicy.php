<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SuratTerbit;
use Illuminate\Auth\Access\HandlesAuthorization;

class SuratTerbitPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SuratTerbit');
    }

    public function view(AuthUser $authUser, SuratTerbit $suratTerbit): bool
    {
        return $authUser->can('View:SuratTerbit');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SuratTerbit');
    }

    public function update(AuthUser $authUser, SuratTerbit $suratTerbit): bool
    {
        return $authUser->can('Update:SuratTerbit');
    }

    public function delete(AuthUser $authUser, SuratTerbit $suratTerbit): bool
    {
        return $authUser->can('Delete:SuratTerbit');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SuratTerbit');
    }

    public function restore(AuthUser $authUser, SuratTerbit $suratTerbit): bool
    {
        return $authUser->can('Restore:SuratTerbit');
    }

    public function forceDelete(AuthUser $authUser, SuratTerbit $suratTerbit): bool
    {
        return $authUser->can('ForceDelete:SuratTerbit');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SuratTerbit');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SuratTerbit');
    }

    public function replicate(AuthUser $authUser, SuratTerbit $suratTerbit): bool
    {
        return $authUser->can('Replicate:SuratTerbit');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SuratTerbit');
    }

}