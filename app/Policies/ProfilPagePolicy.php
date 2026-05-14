<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProfilPage;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilPagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProfilPage');
    }

    public function view(AuthUser $authUser, ProfilPage $profilPage): bool
    {
        return $authUser->can('View:ProfilPage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProfilPage');
    }

    public function update(AuthUser $authUser, ProfilPage $profilPage): bool
    {
        return $authUser->can('Update:ProfilPage');
    }

    public function delete(AuthUser $authUser, ProfilPage $profilPage): bool
    {
        return $authUser->can('Delete:ProfilPage');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ProfilPage');
    }

    public function restore(AuthUser $authUser, ProfilPage $profilPage): bool
    {
        return $authUser->can('Restore:ProfilPage');
    }

    public function forceDelete(AuthUser $authUser, ProfilPage $profilPage): bool
    {
        return $authUser->can('ForceDelete:ProfilPage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProfilPage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProfilPage');
    }

    public function replicate(AuthUser $authUser, ProfilPage $profilPage): bool
    {
        return $authUser->can('Replicate:ProfilPage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProfilPage');
    }

}