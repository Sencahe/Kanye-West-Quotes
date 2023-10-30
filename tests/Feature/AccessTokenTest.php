<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AccessTokenTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_access_token()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'name' => 'Test',
            'last_name' => 'User',
            'password' => bcrypt('password'), 
        ]);

        $response = $this->post('/api/auth/token', [ 
            'email' => 'test@test.com',
            'password' => 'password'
        ]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure ([
            "message",
            "access_token",
            "token_type",
            "user" => ["id","email","name","last_name"],

        ]);
    }
}
