<?php

namespace App\Models;

use App\Models\ExpenseItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseCategory extends Model
{
    /**
     * Get all of the expense_items for the ExpenseCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expense_items(): HasMany
    {
        return $this->hasMany(ExpenseItem::class);
    }
}
