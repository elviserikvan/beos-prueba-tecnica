<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Product::paginate($perPage);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function getWithPrices(Product $product): Product
    {
        $product->load('productPrices');

        return $product;
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function getPrices(Product $product, int $perPage = 15): LengthAwarePaginator
    {
        return $product->productPrices()->paginate($perPage);
    }

    public function createPrice(Product $product, array $data): ProductPrice
    {
        return ProductPrice::create([
            'product_id' => $product->id,
            'currency_id' => $data['currency_id'],
            'price' => $data['price'],
        ]);
    }
}
