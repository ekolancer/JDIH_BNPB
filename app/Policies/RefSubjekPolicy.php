<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RefSubjek;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefSubjekPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_ref_subjek');
    }

    public function view(AuthUser $authUser, RefSubjek $refSubjek): bool
    {
        return $authUser->can('view_ref_subjek');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_ref_subjek');
    }

    public function update(AuthUser $authUser, RefSubjek $refSubjek): bool
    {
        return $authUser->can('update_ref_subjek');
    }

    public function delete(AuthUser $authUser, RefSubjek $refSubjek): bool
    {
        return $authUser->can('delete_ref_subjek');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any_ref_subjek');
    }

    public function restore(AuthUser $authUser, RefSubjek $refSubjek): bool
    {
        return $authUser->can('restore_ref_subjek');
    }

    public function forceDelete(AuthUser $authUser, RefSubjek $refSubjek): bool
    {
        return $authUser->can('force_delete_ref_subjek');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_ref_subjek');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_ref_subjek');
    }

    public function replicate(AuthUser $authUser, RefSubjek $refSubjek): bool
    {
        return $authUser->can('replicate_ref_subjek');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_ref_subjek');
    }

}