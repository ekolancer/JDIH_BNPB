<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RefJenisDokumen;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefJenisDokumenPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_ref_jenis_dokumen');
    }

    public function view(AuthUser $authUser, RefJenisDokumen $refJenisDokumen): bool
    {
        return $authUser->can('view_ref_jenis_dokumen');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_ref_jenis_dokumen');
    }

    public function update(AuthUser $authUser, RefJenisDokumen $refJenisDokumen): bool
    {
        return $authUser->can('update_ref_jenis_dokumen');
    }

    public function delete(AuthUser $authUser, RefJenisDokumen $refJenisDokumen): bool
    {
        return $authUser->can('delete_ref_jenis_dokumen');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any_ref_jenis_dokumen');
    }

    public function restore(AuthUser $authUser, RefJenisDokumen $refJenisDokumen): bool
    {
        return $authUser->can('restore_ref_jenis_dokumen');
    }

    public function forceDelete(AuthUser $authUser, RefJenisDokumen $refJenisDokumen): bool
    {
        return $authUser->can('force_delete_ref_jenis_dokumen');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_ref_jenis_dokumen');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_ref_jenis_dokumen');
    }

    public function replicate(AuthUser $authUser, RefJenisDokumen $refJenisDokumen): bool
    {
        return $authUser->can('replicate_ref_jenis_dokumen');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_ref_jenis_dokumen');
    }

}