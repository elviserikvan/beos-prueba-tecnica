<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function getAll(): Collection
    {
        return Product::all();
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

    public function getPrices(Product $product): Collection
    {
        return $product->productPrices;
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
