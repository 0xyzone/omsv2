<?php

namespace App\Models;

use App\Models\ExpenseCategory;
use App\Models\ExpenseRecordItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseItem extends Model
{
    /**
     * Get the expense_category that owns the ExpenseItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expense_category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    /**
     * Get all of the expense_record_items for the ExpenseItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expense_record_items(): HasMany
    {
        return $this->hasMany(ExpenseRecordItem::class);
    }
}
