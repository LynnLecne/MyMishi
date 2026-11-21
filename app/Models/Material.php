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

      // Chave = nome real do ficheiro | Valor = legenda apresentada na vista
   public const AVAILABLE_IMAGES = [
    'mat-01.png', 'mat-02.png', 'mat-03.png', 'mat-04.png',
    'mat-05.png', 'mat-06.png', 'mat-07.png', 'mat-08.png',
    'mat-09.png', 'mat-10.png', 'mat-11.png', 'mat-12.png',
    'mat-13.png',
];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

/**
 * Verifica se o material está com stock baixo conforme a sua unidade.
 */
public function isLowStock(): bool
{
    return $this->unit === 'm'
        ? $this->quantity < 0.5
        : $this->quantity < 4;
}

public function scopeLowStock($query)
{
    return $query->where(function ($q) {
        $q->where(function ($sub) {
            $sub->where('unit', 'm')->where('quantity', '<', 0.5);
        })->orWhere(function ($sub) {
            $sub->where('unit', '!=', 'm')->where('quantity', '<', 4);
        });
    });
}

    public function products(): BelongsToMany
{
    return $this->belongsToMany(Product::class)
                ->withPivot('quantity_used')
                ->withTimestamps();
}
}
