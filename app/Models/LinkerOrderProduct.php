<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkerOrderProduct extends Model
{
    protected $table = 'linker_order_products';

    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(LinkerOrder::class, 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(LinkerProduct::class, 'product_id');
    }
}
