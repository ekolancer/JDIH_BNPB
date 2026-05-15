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
        return $authUser->can('view_any_ref_bidang_hukum');
    }

    public function view(AuthUser $authUser, RefBidangHukum $refBidangHukum): bool
    {
        return $authUser->can('view_ref_bidang_hukum');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_ref_bidang_hukum');
    }

    public function update(AuthUser $authUser, RefBidangHukum $refBidangHukum): bool
    {
        return $authUser->can('update_ref_bidang_hukum');
    }

    public function delete(AuthUser $authUser, RefBidangHukum $refBidangHukum): bool
    {
        return $authUser->can('delete_ref_bidang_hukum');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any_ref_bidang_hukum');
    }

    public function restore(AuthUser $authUser, RefBidangHukum $refBidangHukum): bool
    {
        return $authUser->can('restore_ref_bidang_hukum');
    }

    public function forceDelete(AuthUser $authUser, RefBidangHukum $refBidangHukum): bool
    {
        return $authUser->can('force_delete_ref_bidang_hukum');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_ref_bidang_hukum');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_ref_bidang_hukum');
    }

    public function replicate(AuthUser $authUser, RefBidangHukum $refBidangHukum): bool
    {
        return $authUser->can('replicate_ref_bidang_hukum');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_ref_bidang_hukum');
    }

}