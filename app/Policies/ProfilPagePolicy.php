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
        return $authUser->can('view_any_profil_page');
    }

    public function view(AuthUser $authUser, ProfilPage $profilPage): bool
    {
        return $authUser->can('view_profil_page');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_profil_page');
    }

    public function update(AuthUser $authUser, ProfilPage $profilPage): bool
    {
        return $authUser->can('update_profil_page');
    }

    public function delete(AuthUser $authUser, ProfilPage $profilPage): bool
    {
        return $authUser->can('delete_profil_page');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any_profil_page');
    }

    public function restore(AuthUser $authUser, ProfilPage $profilPage): bool
    {
        return $authUser->can('restore_profil_page');
    }

    public function forceDelete(AuthUser $authUser, ProfilPage $profilPage): bool
    {
        return $authUser->can('force_delete_profil_page');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_profil_page');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_profil_page');
    }

    public function replicate(AuthUser $authUser, ProfilPage $profilPage): bool
    {
        return $authUser->can('replicate_profil_page');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_profil_page');
    }

}