<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Infografis;
use Illuminate\Auth\Access\HandlesAuthorization;

class InfografisPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_infografis');
    }

    public function view(AuthUser $authUser, Infografis $infografis): bool
    {
        return $authUser->can('view_infografis');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_infografis');
    }

    public function update(AuthUser $authUser, Infografis $infografis): bool
    {
        return $authUser->can('update_infografis');
    }

    public function delete(AuthUser $authUser, Infografis $infografis): bool
    {
        return $authUser->can('delete_infografis');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any_infografis');
    }

    public function restore(AuthUser $authUser, Infografis $infografis): bool
    {
        return $authUser->can('restore_infografis');
    }

    public function forceDelete(AuthUser $authUser, Infografis $infografis): bool
    {
        return $authUser->can('force_delete_infografis');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_infografis');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_infografis');
    }

    public function replicate(AuthUser $authUser, Infografis $infografis): bool
    {
        return $authUser->can('replicate_infografis');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_infografis');
    }

}