<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RefBidangHukum;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefBidangHukumPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RefBidangHukum');
    }

    public function view(AuthUser $authUser, RefBidangHukum $refBidangHukum): bool
    {
        return $authUser->can('View:RefBidangHukum');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RefBidangHukum');
    }

    public function update(AuthUser $authUser, RefBidangHukum $refBidangHukum): bool
    {
        return $authUser->can('Update:RefBidangHukum');
    }

    public function delete(AuthUser $authUser, RefBidangHukum $refBidangHukum): bool
    {
        return $authUser->can('Delete:RefBidangHukum');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:RefBidangHukum');
    }

    public function restore(AuthUser $authUser, RefBidangHukum $refBidangHukum): bool
    {
        return $authUser->can('Restore:RefBidangHukum');
    }

    public function forceDelete(AuthUser $authUser, RefBidangHukum $refBidangHukum): bool
    {
        return $authUser->can('ForceDelete:RefBidangHukum');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RefBidangHukum');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RefBidangHukum');
    }

    public function replicate(AuthUser $authUser, RefBidangHukum $refBidangHukum): bool
    {
        return $authUser->can('Replicate:RefBidangHukum');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RefBidangHukum');
    }

}