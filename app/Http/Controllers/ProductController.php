<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductPriceRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductPriceResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService) {}

    public function index()
    {
        return ProductResource::collection($this->productService->getAll());
    }

    public function store(StoreProductRequest $request)
    {
        return new ProductResource($this->productService->create($request->validated()));
    }

    public function show(Product $product)
    {
        return new ProductResource($this->productService->getWithPrices($product));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        return new ProductResource($this->productService->update($product, $request->validated()));
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return response()->json(['message' => 'Product deleted']);
    }

    public function prices(Product $product)
    {
        return ProductPriceResource::collection($this->productService->getPrices($product));
    }

    public function storePrice(StoreProductPriceRequest $request, Product $product)
    {
        return new ProductPriceResource($this->productService->createPrice($product, $request->validated()));
    }
}
