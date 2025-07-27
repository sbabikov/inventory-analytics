<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FirmaCatalog extends Model
{
    protected $table = 'firma_catalogs';
    protected $fillable = [
        'name',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(FirmaProduct::class, 'catalog_id');
    }
}
