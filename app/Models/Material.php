<?php

namespace App\Models;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    /**
     * Get all of the stocks for the Material
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
