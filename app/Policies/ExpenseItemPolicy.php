<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ExpenseItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpenseItemPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ExpenseItem');
    }

    public function view(AuthUser $authUser, ExpenseItem $expenseItem): bool
    {
        return $authUser->can('View:ExpenseItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ExpenseItem');
    }

    public function update(AuthUser $authUser, ExpenseItem $expenseItem): bool
    {
        return $authUser->can('Update:ExpenseItem');
    }

    public function delete(AuthUser $authUser, ExpenseItem $expenseItem): bool
    {
        return $authUser->can('Delete:ExpenseItem');
    }

    public function restore(AuthUser $authUser, ExpenseItem $expenseItem): bool
    {
        return $authUser->can('Restore:ExpenseItem');
    }

    public function forceDelete(AuthUser $authUser, ExpenseItem $expenseItem): bool
    {
        return $authUser->can('ForceDelete:ExpenseItem');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ExpenseItem');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ExpenseItem');
    }

    public function replicate(AuthUser $authUser, ExpenseItem $expenseItem): bool
    {
        return $authUser->can('Replicate:ExpenseItem');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ExpenseItem');
    }

}