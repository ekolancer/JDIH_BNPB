<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Faq;
use Illuminate\Auth\Access\HandlesAuthorization;

class FaqPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_faq');
    }

    public function view(AuthUser $authUser, Faq $faq): bool
    {
        return $authUser->can('view_faq');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_faq');
    }

    public function update(AuthUser $authUser, Faq $faq): bool
    {
        return $authUser->can('update_faq');
    }

    public function delete(AuthUser $authUser, Faq $faq): bool
    {
        return $authUser->can('delete_faq');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any_faq');
    }

    public function restore(AuthUser $authUser, Faq $faq): bool
    {
        return $authUser->can('restore_faq');
    }

    public function forceDelete(AuthUser $authUser, Faq $faq): bool
    {
        return $authUser->can('force_delete_faq');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_faq');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_faq');
    }

    public function replicate(AuthUser $authUser, Faq $faq): bool
    {
        return $authUser->can('replicate_faq');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_faq');
    }

}