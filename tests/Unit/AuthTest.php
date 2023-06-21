<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

class AuthTest extends TestCase
{
    public function testRegisterUser(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->json('POST', '/api/register',[
            "name" => "usertest",
            "email" => "test9@test.com",
            "password" => "291089"
        ]);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['status', 'message', 'token', 'expires'])
        );

        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "User Created Successfully"]);
    }

    public function testRegisterUserEmailExists(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST', '/api/register',[
            "name" => "usertest",
            "email" => "test9@test.com",
            "password" => "291089"
        ]);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['status', 'message', 'errors'])
        );

        $response->assertJson(['status' => false]);
        $response ->assertJsonStructure([
            "status",
            "message",
            "errors" => [
                "email" 
            ]
        ]);
    }

    public function testLoginUser(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST', '/api/login',[
            "email" => "test9@test.com",
            "password" => "291089"
        ]);


        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['status', 'message', 'token', 'expires'])
        );

        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Generated token"]);
    }

    public function testLoginUserWithIncorrectEmail(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST', '/api/login',[
            "email" => "test10@test.com",
            "password" => "291089"
        ]);

        $response->assertUnprocessable();
        $response->assertJson(['message' => "The provided credentials are incorrect."]);       
    }

    public function testLoginUserWithIncorrectPassword(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST', '/api/login',[
            "email" => "usertest",
            "password" => "29108990"
        ]);

        $response->assertUnprocessable();
        $response->assertJson(['message' => "The email field must be a valid email address."]);       
    }
}
