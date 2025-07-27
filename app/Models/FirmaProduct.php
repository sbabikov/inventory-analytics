<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FirmaProduct extends Model
{
    protected $table = 'firma_products';
    protected $fillable = [
        'name', 'sku', 'ean', 'price', 'quantity', 'catalog_id'
    ];

    public function catalog(): BelongsTo
    {
        return $this->belongsTo(FirmaCatalog::class, 'catalog_id');
    }
}
