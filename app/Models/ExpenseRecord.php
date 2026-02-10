<?php

namespace App\Models;

use App\Models\User;
use App\Models\ExpenseRecordItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseRecord extends Model
{
    /**
     * Get all of the expense_record_item for the ExpenseRecord
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expense_record_items(): HasMany
    {
        return $this->hasMany(ExpenseRecordItem::class);
    }

    /**
     * Get the user that owns the ExpenseRecord
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
