<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JenisSurat;
use Illuminate\Auth\Access\HandlesAuthorization;

class JenisSuratPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JenisSurat');
    }

    public function view(AuthUser $authUser, JenisSurat $jenisSurat): bool
    {
        return $authUser->can('View:JenisSurat');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JenisSurat');
    }

    public function update(AuthUser $authUser, JenisSurat $jenisSurat): bool
    {
        return $authUser->can('Update:JenisSurat');
    }

    public function delete(AuthUser $authUser, JenisSurat $jenisSurat): bool
    {
        return $authUser->can('Delete:JenisSurat');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:JenisSurat');
    }

    public function restore(AuthUser $authUser, JenisSurat $jenisSurat): bool
    {
        return $authUser->can('Restore:JenisSurat');
    }

    public function forceDelete(AuthUser $authUser, JenisSurat $jenisSurat): bool
    {
        return $authUser->can('ForceDelete:JenisSurat');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JenisSurat');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JenisSurat');
    }

    public function replicate(AuthUser $authUser, JenisSurat $jenisSurat): bool
    {
        return $authUser->can('Replicate:JenisSurat');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JenisSurat');
    }

}