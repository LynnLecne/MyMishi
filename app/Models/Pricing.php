<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pricing extends Model
{
    protected $fillable = [
    'product_id',
    'materials_cost',
    'hourly_rate',
    'hours_spent',
    'labor_cost',
    'packaging_cost',
    'profit_margin',
    'final_price',
];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
