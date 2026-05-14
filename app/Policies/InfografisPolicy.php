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
        return $authUser->can('ViewAny:Infografis');
    }

    public function view(AuthUser $authUser, Infografis $infografis): bool
    {
        return $authUser->can('View:Infografis');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Infografis');
    }

    public function update(AuthUser $authUser, Infografis $infografis): bool
    {
        return $authUser->can('Update:Infografis');
    }

    public function delete(AuthUser $authUser, Infografis $infografis): bool
    {
        return $authUser->can('Delete:Infografis');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Infografis');
    }

    public function restore(AuthUser $authUser, Infografis $infografis): bool
    {
        return $authUser->can('Restore:Infografis');
    }

    public function forceDelete(AuthUser $authUser, Infografis $infografis): bool
    {
        return $authUser->can('ForceDelete:Infografis');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Infografis');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Infografis');
    }

    public function replicate(AuthUser $authUser, Infografis $infografis): bool
    {
        return $authUser->can('Replicate:Infografis');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Infografis');
    }

}