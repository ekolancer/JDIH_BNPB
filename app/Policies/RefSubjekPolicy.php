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
        return $authUser->can('ViewAny:RefSubjek');
    }

    public function view(AuthUser $authUser, RefSubjek $refSubjek): bool
    {
        return $authUser->can('View:RefSubjek');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RefSubjek');
    }

    public function update(AuthUser $authUser, RefSubjek $refSubjek): bool
    {
        return $authUser->can('Update:RefSubjek');
    }

    public function delete(AuthUser $authUser, RefSubjek $refSubjek): bool
    {
        return $authUser->can('Delete:RefSubjek');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:RefSubjek');
    }

    public function restore(AuthUser $authUser, RefSubjek $refSubjek): bool
    {
        return $authUser->can('Restore:RefSubjek');
    }

    public function forceDelete(AuthUser $authUser, RefSubjek $refSubjek): bool
    {
        return $authUser->can('ForceDelete:RefSubjek');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RefSubjek');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RefSubjek');
    }

    public function replicate(AuthUser $authUser, RefSubjek $refSubjek): bool
    {
        return $authUser->can('Replicate:RefSubjek');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RefSubjek');
    }

}