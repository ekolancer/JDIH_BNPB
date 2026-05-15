<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Visitor;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitorPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_visitor');
    }

    public function view(AuthUser $authUser, Visitor $visitor): bool
    {
        return $authUser->can('view_visitor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_visitor');
    }

    public function update(AuthUser $authUser, Visitor $visitor): bool
    {
        return $authUser->can('update_visitor');
    }

    public function delete(AuthUser $authUser, Visitor $visitor): bool
    {
        return $authUser->can('delete_visitor');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any_visitor');
    }

    public function restore(AuthUser $authUser, Visitor $visitor): bool
    {
        return $authUser->can('restore_visitor');
    }

    public function forceDelete(AuthUser $authUser, Visitor $visitor): bool
    {
        return $authUser->can('force_delete_visitor');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_visitor');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_visitor');
    }

    public function replicate(AuthUser $authUser, Visitor $visitor): bool
    {
        return $authUser->can('replicate_visitor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_visitor');
    }

}