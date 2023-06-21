<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Client;
use App\Models\User;

class ClientTest extends TestCase
{
    public function testListAllClients()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('GET', '/api/client');

        $response->assertOk();
        $response->assertJsonCount(20, 'clients');
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Successfully"]);
    }


    public function testFoundClient()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('GET', '/api/client/1');

        $response->assertOk();
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Client Found Successfully"]);
    }

    public function testNotFoundClient()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('GET', '/api/client/22');

        $response->assertNotFound();
    }

    public function testCreateClient()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST', '/api/client',[
            'name' => 'Jose rodrigues',
            'email' => 'jose@gmail.com',
            'phonenumber' => '(11)987340324',
            'dateofbirth' => '1989-10-29',
            'address' => "Avenue test 20",
            'complement' => "House 5",
            'neighborhood' => "Alabama",
            'zipcode' => "09778-000"
        ]);

        $response->assertOk();
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Client Created Successfully"]);
        $response->assertJsonPath('client.name', 'Jose rodrigues');
        $response->assertJsonPath('client.email', 'jose@gmail.com');
    }

    public function testCreateClientWhenEmailExists()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST', '/api/client',[
            'name' => 'Mario Rodrigues',
            'email' => 'jose@gmail.com',
            'phonenumber' => '(11)987350224',
            'dateofbirth' => '1987-01-29',
            'address' => "Avenue test 4",
            'complement' => "House 2",
            'neighborhood' => "Center",
            'zipcode' => "08798-000"
        ]);

        $response->assertUnprocessable();
        $response->assertJsonPath('message', 'The email has already been taken.');
    }

    public function testUpdateClient()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('PUT','/api/client/1', [
            'name' => 'Jose maria',
            'email' => 'josemaria@gmail.com',
            'phonenumber' => '(11)987340324',
            'dateofbirth' => '1989-10-29',
            'address' => "Avenue test 20",
            'complement' => "House 5",
            'neighborhood' => "Alabama",
            'zipcode' => "09778-000"
        ]);

        $response->assertOk();
        $response->assertJson(['message' => "Client Updated Successfully"]);
        $response->assertJson(['status' => true]);
        $response->assertJsonPath('client.name','Jose maria');
        $response->assertJsonPath('client.email', 'josemaria@gmail.com');
    }


    public function testUpdateClientWhenEmailExistsEmailIsFromClient()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('PUT','/api/client/1', [
            'name' => 'Jose maria rodrigues de alcantara',
            'email' => 'josemaria@gmail.com',
            'phonenumber' => '(11)987340324',
            'dateofbirth' => '1989-10-29',
            'address' => "Avenue test 20",
            'complement' => "House 5",
            'neighborhood' => "Alabama",
            'zipcode' => "09778-000"
        ]);

        $response->assertOk();
        $response->assertJson(['message' => "Client Updated Successfully"]);
        $response->assertJson(['status' => true]);
        $response->assertJsonPath('client.name','Jose maria rodrigues de alcantara');
        $response->assertJsonPath('client.email', 'josemaria@gmail.com');
    }

    public function testUpdateClientChangeEmailWhenEmailExistsAndEmailIsNotFromClient()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('PUT','/api/client/1', [
            'name' => 'Jose maria rodrigues de alcantara',
            'email' => 'jose@gmail.com',
            'phonenumber' => '(11)987340324',
            'dateofbirth' => '1989-10-29',
            'address' => "Avenue test 20",
            'complement' => "House 5",
            'neighborhood' => "Alabama",
            'zipcode' => "09778-000"
        ]);

        $response->assertUnprocessable();
        $response->assertJsonPath('message', 'The email has already been taken.');
    }

    public function testDeleteClient()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->json('GET', '/api/client');
     
        $result = $response->decodeResponseJson();
        
        $delete = $this->actingAs($user)->json('DELETE', '/api/client/'. $result["clients"][0]["id"]);

        $delete->assertOk();
        $delete->assertJson(['status' => true]);
        $delete->assertJson(['message' => "Client Deleted"]);
    }
}
