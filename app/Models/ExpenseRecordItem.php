<?php

namespace App\Models;

use App\Models\ExpenseRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseRecordItem extends Model
{
    /**
     * Get the expense_record that owns the ExpenseRecordItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expense_record(): BelongsTo
    {
        return $this->belongsTo(ExpenseRecord::class);
    }

    /**
     * Get the expense_item that owns the ExpenseRecordItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expense_item(): BelongsTo
    {
        return $this->belongsTo(ExpenseItem::class);
    }
}
