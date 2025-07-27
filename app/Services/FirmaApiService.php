<?php

namespace App\Services;

use Illuminate\Support\Str;
use Faker\Factory as Faker;

/**
 * Клас-імітація SDK для взаємодії зі складською системою Firma.
 * Дані генеруються динамічно при кожному запиті.
 */
class FirmaApiService
{
    /**
     * Отримати перелік доступних каталогів.
     *
     * @return array<int, array{id: int, name: string}>
     */
    public function getCatalogs(): array
    {
        return collect(range(1, 10))
            ->map(fn(int $id) => [
                'id'   => $id,
                'name' => "Каталог №$id",
            ])
            ->toArray();
    }

    /**
     * Отримати перелік товарів з базовими характеристиками.
     *
     * @return array<int, array{
     *     id: int,
     *     name: string,
     *     sku: string,
     *     ean: string,
     *     price: float,
     *     quantity: int,
     *     catalog_id: int
     * }>
     */
    public function getProducts(): array
    {
        $faker = Faker::create();
        $catalogIds = range(1, 10);

        return collect(range(1, 10000))
            ->map(fn(int $id) => [
                'id'         => $id,
                'name'       => "Товар #{$id}",
                'sku'        => 'SKU-' . Str::padLeft((string)$id, 5, '0'),
                'ean'        => (string) $faker->ean13(),
                'price'      => $faker->randomFloat(2, 10, 1000),
                'quantity'   => $faker->numberBetween(0, 150),
                'catalog_id' => $faker->randomElement($catalogIds),
            ])
            ->toArray();
    }
}
