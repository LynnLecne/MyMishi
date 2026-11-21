<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'image', 'user_id'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

  public const AVAILABLE_IMAGES = [
    'cat-01.png', 'cat-02.png', 'cat-03.png', 'cat-04.png',
    'cat-05.png', 'cat-06.png', 'cat-07.png', 'cat-08.png'
];

}
