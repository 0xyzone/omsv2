<?php

namespace App\Models;

use App\Models\Material;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    /**
     * Get the material that owns the Stock
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
