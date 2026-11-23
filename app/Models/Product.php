<?php

namespace App\Models;

use App\Models\Pricing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = ['category_id', 'user_id', 'name', 'image', 'quantity'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


public function pricing(): HasOne
{
    return $this->hasOne(Pricing::class);
}
public function materials(): BelongsToMany
{
    return $this->belongsToMany(Material::class)
                ->withPivot('quantity_used')
                ->withTimestamps();
}


public function isLowStock(): bool
{
    return $this->quantity < 2;
}

public function scopeLowStock($query)
{
    return $query->where('quantity', '<', 2);
}


}


