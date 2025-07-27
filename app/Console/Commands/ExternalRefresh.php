<?php

namespace App\Console\Commands;

use App\Models\LinkerOrder;
use App\Models\LinkerOrderProduct;
use Illuminate\Console\Command;
use App\Services\FirmaApiService;
use App\Services\LinkerApiService;
use App\Models\FirmaProduct;
use App\Models\FirmaCatalog;
use App\Models\LinkerProduct;
use Illuminate\Support\Facades\DB;

class ExternalRefresh extends Command
{
    protected $signature = 'external:refresh';
    protected $description = 'Refresh data from Firma and Linker APIs (mocked)';

    public function handle()
    {
        $this->info('🔄 Starting data refresh from SDKs...');
        \Log::info('ExternalRefresh command has been executed: ' . now());
        $start = microtime(true);

        $firma = new FirmaApiService();
        $linker = new LinkerApiService();

        DB::transaction(function () use ($firma, $linker) {

            // === Firma Catalogs ===
            $this->info('📁 Updating Firma catalogs...');
            $catalogs = $firma->getCatalogs();
            $this->output->progressStart(count($catalogs));

            foreach ($catalogs as $catalogData) {
                FirmaCatalog::updateOrCreate(
                    ['id' => $catalogData['id']],
                    ['name' => $catalogData['name']]
                );
                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->info('✅ Firma catalogs updated.');

            // === Firma Products ===
            $this->info('📦 Updating Firma products...');
            $firmaProducts = $firma->getProducts();
            $this->output->progressStart(count($firmaProducts));

            foreach ($firmaProducts as $productData) {
                FirmaProduct::updateOrCreate(
                    ['id' => $productData['id']],
                    [
                        'name'       => $productData['name'],
                        'sku'        => $productData['sku'],
                        'ean'        => $productData['ean'],
                        'price'      => $productData['price'],
                        'quantity'   => $productData['quantity'],
                        'catalog_id' => $productData['catalog_id'],
                    ]
                );
                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->info('✅ Firma products updated.');

            // === Linker Products ===
            $this->info('🔗 Updating Linker products...');
            $linkerProducts = $linker->getProducts();
            $this->output->progressStart(count($linkerProducts));

            foreach ($linkerProducts as $productData) {
                LinkerProduct::updateOrCreate(
                    ['id' => $productData['id']],
                    [
                        'name'             => $productData['name'],
                        'sku'              => $productData['sku'],
                        'ean'              => $productData['ean'],
                        'price'            => $productData['price'],
                        'quantity'         => $productData['quantity'],
                        'firma_product_id' => $productData['firma_product_id'],
                    ]
                );
                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->info('✅ Linker products updated.');

            // === Orders and Order Products ===
            $this->info('🧾 Updating Orders and Order Products...');
            $orders = $linker->getOrders();
            $this->output->progressStart(count($orders));

            foreach ($orders as $orderData) {
                $order = LinkerOrder::updateOrCreate(
                    ['id' => $orderData['id']],
                    [
                        'source' => $orderData['source'],
                        'total'  => $orderData['total'],
                        'date'   => $orderData['date'],
                    ]
                );

                foreach ($orderData['products'] as $op) {
                    LinkerOrderProduct::updateOrCreate(
                        ['id' => $op['id']],
                        [
                            'order_id'   => $order->id,
                            'product_id' => $op['product_id'],
                            'price'      => $op['price'],
                            'quantity'   => $op['quantity'],
                        ]
                    );
                }

                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->info('✅ Orders and Order Products updated.');
        });

        $duration = round(microtime(true) - $start, 2);
        $this->info("🏁 Done in {$duration} seconds.");
    }
}
