<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function getProducts($catalogId)
    {
        return DB::table('firma_products')
            ->when($catalogId, fn($q) => $q->where('firma_products.catalog_id', $catalogId))
            ->leftJoin('linker_products', 'firma_products.id', '=', 'linker_products.firma_product_id')
            ->leftJoin('linker_order_products', 'linker_products.id', '=', 'linker_order_products.product_id')
            ->leftJoin('linker_orders', 'linker_order_products.order_id', '=', 'linker_orders.id')
            ->select(
                'firma_products.id as firma_id',
                'linker_products.id as linker_id',
                'firma_products.name',
                'firma_products.sku',
                'firma_products.ean',
                'firma_products.quantity as firma_stock',
                'linker_products.quantity as linker_stock',
                'firma_products.price as firma_price',
                'linker_products.price as linker_price',
                DB::raw('COUNT(linker_order_products.id) as total_sales'),
                DB::raw('ROUND(SUM(CASE WHEN linker_orders.created_at >= NOW() - INTERVAL 7 DAY THEN linker_order_products.quantity ELSE 0 END) / 7, 2) as average_sales_7_days'),
            )
            ->groupBy('firma_products.id', 'linker_products.id')
            ->paginate(20);
    }

    public function getSalesBySource($firmaProductId)
    {
        $cacheKey = "product:{$firmaProductId}:sales_by_source";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($firmaProductId) {
            return DB::table('linker_order_products')
                ->join('linker_orders', 'linker_order_products.order_id', '=', 'linker_orders.id')
                ->where('linker_order_products.product_id', $firmaProductId)
                ->select('linker_orders.source', DB::raw('SUM(linker_order_products.quantity) as total'))
                ->groupBy('linker_orders.source')
                ->pluck('total', 'linker_orders.source')
                ->toArray();
        });
    }

    public function getCatalogs()
    {
        return DB::table('firma_catalogs')->get();
    }
}
