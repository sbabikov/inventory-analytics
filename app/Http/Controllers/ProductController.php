<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $catalogId = $request->get('catalog_id');
        $products = $this->service->getProducts($catalogId);

        foreach ($products as $product) {
            $product->sales_by_source = $this->service->getSalesBySource($product->firma_id);
        }

        $catalogs = $this->service->getCatalogs();

        return view('products.index', compact('products', 'catalogs'));
    }
}
