<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DokumenHukum;
use Illuminate\Auth\Access\HandlesAuthorization;

class DokumenHukumPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DokumenHukum');
    }

    public function view(AuthUser $authUser, DokumenHukum $dokumenHukum): bool
    {
        return $authUser->can('View:DokumenHukum');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DokumenHukum');
    }

    public function update(AuthUser $authUser, DokumenHukum $dokumenHukum): bool
    {
        return $authUser->can('Update:DokumenHukum');
    }

    public function delete(AuthUser $authUser, DokumenHukum $dokumenHukum): bool
    {
        return $authUser->can('Delete:DokumenHukum');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DokumenHukum');
    }

    public function restore(AuthUser $authUser, DokumenHukum $dokumenHukum): bool
    {
        return $authUser->can('Restore:DokumenHukum');
    }

    public function forceDelete(AuthUser $authUser, DokumenHukum $dokumenHukum): bool
    {
        return $authUser->can('ForceDelete:DokumenHukum');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DokumenHukum');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DokumenHukum');
    }

    public function replicate(AuthUser $authUser, DokumenHukum $dokumenHukum): bool
    {
        return $authUser->can('Replicate:DokumenHukum');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DokumenHukum');
    }

}