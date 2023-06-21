<?php

namespace Tests\Unit;
use App\Models\Product;
use App\Models\User;
use App\Models\Client;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
   
    public function testCreateOrder()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST', '/api/order',[
            'clientId' => 1,
            'products' => [1,2,3],
        ]);

        $response->assertOk();
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Order Created Successfully"]);
        $response->assertJsonCount(3, 'items');
        $response->assertJsonPath('items.0.product_id',1);
        $response->assertJsonPath('items.1.product_id',2);
        $response->assertJsonPath('items.2.product_id',3);
        $response->assertJsonPath('items.0.client_id',1);
        $response->assertJsonPath('items.1.client_id',1);
        $response->assertJsonPath('items.2.client_id',1);
    }

    public function testFoundOrder()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST', '/api/order',[
            'clientId' => 1,
            'products' => [1,2,3],
        ]);

        $response->assertOk();

        $response = $this->actingAs($user)->json('GET', '/api/order/1');
        $response->assertOk();
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Order Found Successfully"]);
        $response->assertJsonCount(3, 'items');
    }

    public function testUpdateOrder()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('PUT','/api/order/1', [
            'clientId' => 1,
            'products' => [2,3,4],
            '_method' => 'put'
        ]);

        $response->assertOk();
        $response->assertJson(['message' => "Update Order Successfully"]);
        $response->assertJson(['status' => true]);
        $response->assertJsonCount(3, 'items');
        $response->assertJsonPath('items.0.product_id',2);
        $response->assertJsonPath('items.1.product_id',3);
        $response->assertJsonPath('items.2.product_id',4);
        $response->assertJsonPath('items.0.client_id',1);
        $response->assertJsonPath('items.1.client_id',1);
        $response->assertJsonPath('items.2.client_id',1);
    }


    public function testListAllOrder()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST','/api/order', [
            'clientId' => 1,
            'products' => [1,2,3]
        ]);

        $response->assertOk();

        $response = $this->actingAs($user)->json('POST','/api/order', [
            'clientId' => 2,
            'products' => [2,3,4]
        ]);

        $response->assertOk();

        $response = $this->actingAs($user)->json('GET', '/api/order');

        $response->assertOk();
        $response->assertJsonCount(2, 'ordersclients');
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Orders Found Successfully"]);
    }


    public function testDeleteOrder()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->json('POST','/api/order', [
            'clientId' => 1,
            'products' => [1,2,3]
        ]);
        $response->assertOk();
        $response = $this->actingAs($user)->json('DELETE','api/order/1');
        $response->assertOk();
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Order Deleted"]);
    }

    public function testDeleteOrderThatDoesNotExist()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->json('DELETE','api/order/3');
        $response->assertUnauthorized();
        $response->assertJson(['message' => "There is no order from this client today to be canceled"]);
    }   
}