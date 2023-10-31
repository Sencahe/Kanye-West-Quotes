<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */

    public function test_login_success()
    {

        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/auth', [
            'email' => 'test@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure ([
            "message",
            "user" => ["id","email","name","last_name"],

        ]);
    }

    public function test_login_fail()
    {

        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/auth', [
            'email' => 'test@test.com',
            'password' => 'invalidPassword',
        ]);

        $response->assertStatus(401);
        $response->assertJsonFragment ([
            "message" => "Email and/or Password are incorrect!"

        ]);

    }

    public function test_register_user(){
        $user = [
            "name" => "Test",
            "last_name" => "User",
            "email" => "test@test.com",
            "password" => "password",
            "confirm_password" => "password"
        ];

        $response = $this->post('/auth/register', $user);
        $response->assertStatus(200);
        $response->assertJsonFragment ([
            "message" => "User successfully created!"
        ]);
     }

     public function test_register_user_malformed(){
        $user = [
            "name" => "",
            "last_name" => "",
            "email" => "test.com",
            "password" => "pa",
            "confirm_password" => "password"
        ];

        $response = $this->post('/auth/register', $user);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            "name","last_name","email","password","confirm_password"
        ]);
     }

     public function test_logout_user(){
        $user = User::factory()->create([]);
        Sanctum::actingAs($user, ['*']);

        $response = $this->post('/auth/logout');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            "message" => "You have successfully logged out!"
        ]);
     }

     public function test_logout_user_fail_redirect(){

        $response = $this->post('/auth/logout');
        $response->assertStatus(302);
     }

     
     public function test_check_session(){
        $user = User::factory()->create([]);
        Sanctum::actingAs($user, ['*']);

        $response = $this->get('/auth/session');
        $response->assertJsonStructure([
            "name","last_name","email","created_at","updated_at"
        ]);
        $response->assertStatus(200);
     }
}
