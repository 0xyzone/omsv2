<?php

namespace App\Models;

use App\Models\ExpenseItem;
use App\Models\ExpenseRecordItem;
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

    public function expense_record_items(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
{
    return $this->hasManyThrough(
        ExpenseRecordItem::class, 
        ExpenseItem::class,
        'expense_category_id', // Foreign key on ExpenseItem table
        'expense_item_id',     // Foreign key on ExpenseRecordItem table
        'id',                  // Local key on ExpenseCategory table
        'id'                   // Local key on ExpenseItem table
    );
}
}
