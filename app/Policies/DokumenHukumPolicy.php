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
        return $authUser->can('view_any_dokumen_hukum');
    }

    public function view(AuthUser $authUser, DokumenHukum $dokumenHukum): bool
    {
        return $authUser->can('view_dokumen_hukum');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_dokumen_hukum');
    }

    public function update(AuthUser $authUser, DokumenHukum $dokumenHukum): bool
    {
        return $authUser->can('update_dokumen_hukum');
    }

    public function delete(AuthUser $authUser, DokumenHukum $dokumenHukum): bool
    {
        return $authUser->can('delete_dokumen_hukum');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any_dokumen_hukum');
    }

    public function restore(AuthUser $authUser, DokumenHukum $dokumenHukum): bool
    {
        return $authUser->can('restore_dokumen_hukum');
    }

    public function forceDelete(AuthUser $authUser, DokumenHukum $dokumenHukum): bool
    {
        return $authUser->can('force_delete_dokumen_hukum');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_dokumen_hukum');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_dokumen_hukum');
    }

    public function replicate(AuthUser $authUser, DokumenHukum $dokumenHukum): bool
    {
        return $authUser->can('replicate_dokumen_hukum');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_dokumen_hukum');
    }

}