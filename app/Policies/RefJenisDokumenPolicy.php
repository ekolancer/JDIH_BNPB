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
        return $authUser->can('ViewAny:RefJenisDokumen');
    }

    public function view(AuthUser $authUser, RefJenisDokumen $refJenisDokumen): bool
    {
        return $authUser->can('View:RefJenisDokumen');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RefJenisDokumen');
    }

    public function update(AuthUser $authUser, RefJenisDokumen $refJenisDokumen): bool
    {
        return $authUser->can('Update:RefJenisDokumen');
    }

    public function delete(AuthUser $authUser, RefJenisDokumen $refJenisDokumen): bool
    {
        return $authUser->can('Delete:RefJenisDokumen');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:RefJenisDokumen');
    }

    public function restore(AuthUser $authUser, RefJenisDokumen $refJenisDokumen): bool
    {
        return $authUser->can('Restore:RefJenisDokumen');
    }

    public function forceDelete(AuthUser $authUser, RefJenisDokumen $refJenisDokumen): bool
    {
        return $authUser->can('ForceDelete:RefJenisDokumen');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RefJenisDokumen');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RefJenisDokumen');
    }

    public function replicate(AuthUser $authUser, RefJenisDokumen $refJenisDokumen): bool
    {
        return $authUser->can('Replicate:RefJenisDokumen');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RefJenisDokumen');
    }

}