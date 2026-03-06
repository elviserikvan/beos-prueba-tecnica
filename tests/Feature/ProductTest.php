<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // index
    // -------------------------------------------------------------------------

    public function test_index_returns_paginated_products(): void
    {
        $currency = Currency::factory()->create();
        Product::factory(20)->create(['currency_id' => $currency->id]);

        $response = $this->getJson('/api/products');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'name', 'description', 'price', 'currency']],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta'  => ['current_page', 'per_page', 'total'],
            ]);
    }

    public function test_index_respects_per_page_parameter(): void
    {
        $currency = Currency::factory()->create();
        Product::factory(20)->create(['currency_id' => $currency->id]);

        $response = $this->getJson('/api/products?per_page=5');

        $response->assertOk()
            ->assertJsonPath('meta.per_page', 5)
            ->assertJsonCount(5, 'data');
    }

    // -------------------------------------------------------------------------
    // store
    // -------------------------------------------------------------------------

    public function test_store_creates_a_product_and_returns_201(): void
    {
        $currency = Currency::factory()->create();

        $payload = [
            'name'               => 'Laptop Pro',
            'description'        => 'High-end laptop for developers',
            'price'              => 1500.00,
            'currency_id'        => $currency->id,
            'tax_cost'           => 150.00,
            'manufacturing_cost' => 500.00,
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Laptop Pro');

        $this->assertDatabaseHas('products', ['name' => 'Laptop Pro']);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->postJson('/api/products', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'description', 'price', 'currency_id', 'tax_cost', 'manufacturing_cost']);
    }

    public function test_store_validates_unique_name(): void
    {
        $currency = Currency::factory()->create();
        Product::factory()->create(['name' => 'Existing Product', 'currency_id' => $currency->id]);

        $response = $this->postJson('/api/products', [
            'name'               => 'Existing Product',
            'description'        => 'Another description',
            'price'              => 100.00,
            'currency_id'        => $currency->id,
            'tax_cost'           => 10.00,
            'manufacturing_cost' => 20.00,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    // -------------------------------------------------------------------------
    // show
    // -------------------------------------------------------------------------

    public function test_show_returns_product_with_prices(): void
    {
        $currency = Currency::factory()->create();
        $product  = Product::factory()->create(['currency_id' => $currency->id]);
        ProductPrice::factory()->create(['product_id' => $product->id, 'currency_id' => $currency->id]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonStructure(['data' => ['id', 'name', 'productPrices']]);
    }

    public function test_show_returns_404_for_nonexistent_product(): void
    {
        $this->getJson('/api/products/999')->assertNotFound();
    }

    // -------------------------------------------------------------------------
    // update
    // -------------------------------------------------------------------------

    public function test_update_modifies_product(): void
    {
        $currency = Currency::factory()->create();
        $product  = Product::factory()->create(['currency_id' => $currency->id]);

        $response = $this->putJson("/api/products/{$product->id}", ['name' => 'Updated Name']);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Updated Name');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name']);
    }

    // -------------------------------------------------------------------------
    // destroy
    // -------------------------------------------------------------------------

    public function test_destroy_deletes_product_and_returns_204(): void
    {
        $currency = Currency::factory()->create();
        $product  = Product::factory()->create(['currency_id' => $currency->id]);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    // -------------------------------------------------------------------------
    // prices
    // -------------------------------------------------------------------------

    public function test_prices_returns_paginated_prices_for_product(): void
    {
        $currency = Currency::factory()->create();
        $product  = Product::factory()->create(['currency_id' => $currency->id]);
        ProductPrice::factory(10)->create(['product_id' => $product->id, 'currency_id' => $currency->id]);

        $response = $this->getJson("/api/products/{$product->id}/prices");

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'price', 'currency']],
                'meta' => ['current_page', 'per_page', 'total'],
            ]);
    }

    // -------------------------------------------------------------------------
    // storePrice
    // -------------------------------------------------------------------------

    public function test_store_price_creates_price_and_returns_201(): void
    {
        $currency = Currency::factory()->create();
        $product  = Product::factory()->create(['currency_id' => $currency->id]);

        $response = $this->postJson("/api/products/{$product->id}/prices", [
            'currency_id' => $currency->id,
            'price'       => 99.99,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.price', 99.99);

        $this->assertDatabaseHas('product_prices', ['product_id' => $product->id, 'price' => 99.99]);
    }

    public function test_store_price_validates_required_fields(): void
    {
        $currency = Currency::factory()->create();
        $product  = Product::factory()->create(['currency_id' => $currency->id]);

        $response = $this->postJson("/api/products/{$product->id}/prices", []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['currency_id', 'price']);
    }
}
