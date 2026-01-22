<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Encashment;
use Illuminate\Auth\Access\HandlesAuthorization;

class EncashmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Encashment');
    }

    public function view(AuthUser $authUser, Encashment $encashment): bool
    {
        return $authUser->can('View:Encashment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Encashment');
    }

    public function update(AuthUser $authUser, Encashment $encashment): bool
    {
        return $authUser->can('Update:Encashment');
    }

    public function delete(AuthUser $authUser, Encashment $encashment): bool
    {
        return $authUser->can('Delete:Encashment');
    }

    public function restore(AuthUser $authUser, Encashment $encashment): bool
    {
        return $authUser->can('Restore:Encashment');
    }

    public function forceDelete(AuthUser $authUser, Encashment $encashment): bool
    {
        return $authUser->can('ForceDelete:Encashment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Encashment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Encashment');
    }

    public function replicate(AuthUser $authUser, Encashment $encashment): bool
    {
        return $authUser->can('Replicate:Encashment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Encashment');
    }

}