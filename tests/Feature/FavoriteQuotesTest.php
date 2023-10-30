<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;
use App\Models\Quote;


class FavoriteQuotesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_favorite_quotes()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $amount = random_int(1, 10);
        $quotes = Quote::factory($amount)->create([
            "user_id"=> $user->id,
        ]);
        
        $response = $this->get('api/quotes/');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['user_id', 'quote','id','updated_at','created_at'] //
        ]);     
        $response->assertJsonCount($amount);
    }
}
