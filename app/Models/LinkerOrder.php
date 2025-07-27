<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LinkerOrder extends Model
{
    protected $table = 'linker_orders';

    protected $fillable = [
        'source',
        'total',
        'date',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'date' => 'datetime',
    ];

    public function orderProducts(): HasMany
    {
        return $this->hasMany(LinkerOrderProduct::class, 'order_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            LinkerProduct::class,
            'linker_order_products',
            'order_id',
            'product_id'
        )->withPivot(['price', 'quantity'])->withTimestamps();
    }
}
