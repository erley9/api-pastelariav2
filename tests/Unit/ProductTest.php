<?php

namespace Tests\Unit;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function testListAllProducts()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('GET', '/api/product');

        $response->assertOk();
        $response->assertJsonCount(20, 'products');
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Successfully"]);
    }

    public function testFoundProduct()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('GET', '/api/product/1');

        $response->assertOk();
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Product Found Successfully"]);
    }

    public function testNotFoundProduct()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('GET', '/api/product/22');

        $response->assertNotFound();
    }

    public function testCreateProduct()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST', '/api/product',[
            'name' => 'New Product',
            'price' => '15.00',
            'photo' => UploadedFile::fake()->image('photo1.png'),
        ]);

        $response->assertOk();
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Product Created Successfully"]);
        $response->assertJsonPath('product.name', 'New Product');
        $response->assertJsonPath('product.price', '15.00');
    }

    public function testUpdateProduct()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('PUT','/api/product/1', [
            'name' => 'New Product2',
            'price' => '16.00',
            'photo' => UploadedFile::fake()->image('photo2.png'),
            '_method' => 'put'
        ]);

        $response->assertOk();
        $response->assertJson(['message' => "Update Product Successfully"]);
        $response->assertJson(['status' => true]);
        $response->assertJsonPath('product.name','New Product2');
        $response->assertJsonPath('product.price', '16.00');

    }

    public function testDeleteProduct()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->json('GET', '/api/product');
     
        $result = $response->decodeResponseJson();
        
        $response = $this->actingAs($user)->json('DELETE', '/api/product/'. $result["products"][0]["id"]);
        $response->assertOk();
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Product Deleted"]);
    }
}
