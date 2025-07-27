<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LinkerProduct extends Model
{
    protected $table = 'linker_products';

    protected $fillable = [
        'name',
        'sku',
        'ean',
        'price',
        'quantity',
        'firma_product_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'firma_product_id' => 'integer',
    ];

    public function firmaProduct(): BelongsTo
    {
        return $this->belongsTo(FirmaProduct::class, 'firma_product_id');
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(LinkerOrderProduct::class, 'product_id');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(
            LinkerOrder::class,
            'linker_order_products',
            'product_id',
            'order_id'
        )->withPivot(['price', 'quantity'])->withTimestamps();
    }
}
