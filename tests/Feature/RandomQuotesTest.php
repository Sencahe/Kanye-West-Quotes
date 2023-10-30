<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;


class RandomQuotesTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_random_quotes()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $amount = random_int(1, 10);

        $response = $this->get('api/random_quotes/' . $amount);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "amount",
            "quotes" => []
        ]);
        $response->assertJsonCount($amount, 'quotes');
    }
}
