<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'unit',
        'quantity',
        'unit_cost',
        'image',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Considera stock baixo quando tem 2 ou menos unidades
    public function isLowStock(): bool
    {
        return $this->quantity <= 2;
    }

    public function products(): BelongsToMany
{
    return $this->belongsToMany(Product::class)
                ->withPivot('quantity_used')
                ->withTimestamps();
}
}
