<?php

namespace App\Services;

use Faker\Factory as Faker;
use Illuminate\Support\Str;

/**
 * Клас-імітація SDK для взаємодії з CRM-системою Linker.
 * Дані генеруються динамічно при кожному запиті.
 */
class LinkerApiService
{
    /**
     * Отримати перелік товарів у CRM.
     * Дані можуть відрізнятись від складських (ціна, кількість).
     *
     * @return array<int, array{
     *     id: int,
     *     name: string,
     *     sku: string,
     *     ean: string,
     *     price: float,
     *     quantity: int,
     *     firma_product_id: int
     * }>
     */
    public function getProducts(): array
    {
        $faker = Faker::create();

        return collect(range(1, 8000)) // CRM може мати не всі товари зі складу
        ->map(fn(int $id) => [
            'id'               => $id,
            'name'             => "Товар #{$id}",
            'sku'              => 'SKU-' . Str::padLeft((string)$id, 5, '0'),
            'ean'              => (string) $faker->ean13(),
            'price'            => $faker->randomFloat(2, 12, 1200), // можлива націнка
            'quantity'         => $faker->numberBetween(0, 80),     // не всі доступні
            'firma_product_id' => $id, // співставлення з товаром зі складу
        ])
            ->toArray();
    }

    /**
     * Отримати список замовлень із CRM.
     *
     * @return array<int, array{
     *     id: int,
     *     source: string,
     *     total: float,
     *     date: string,
     *     products: array<int, array{
     *         id: int,
     *         product_id: int,
     *         price: float,
     *         quantity: int
     *     }>
     * }>
     */
    public function getOrders(): array
    {
        $faker = Faker::create();
        $sources = ['rozetka', 'prom', 'hotline', 'facebook', 'own_site'];
        $orders = [];

        foreach (range(1, 200) as $orderId) {
            $productCount = rand(1, 5);
            $products = [];

            for ($i = 1; $i <= $productCount; $i++) {
                $productId = rand(1, 800);

                $products[] = [
                    'id'         => ($orderId * 10) + $i, // унікальний ID позиції
                    'product_id' => $productId,
                    'price'      => $faker->randomFloat(2, 10, 1500),
                    'quantity'   => $faker->numberBetween(1, 5),
                ];
            }

            $total = collect($products)->sum(fn($p) => $p['price'] * $p['quantity']);

            $orders[] = [
                'id'       => $orderId,
                'source'   => $faker->randomElement($sources),
                'total'    => $total,
                'date'     => $faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d H:i:s'),
                'products' => $products,
            ];
        }

        return $orders;
    }
}
