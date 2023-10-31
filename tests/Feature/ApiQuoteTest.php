<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;
use App\Models\Quote;


class ApiQuoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_random_quotes()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $amount = random_int(1, 10);

        $response = $this->json('get', 'api/random_quotes/' . $amount);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "amount",
            "quotes" => []
        ]);
        $response->assertJsonCount($amount, 'quotes');
    }

    public function test_get_random_quotes_unauthenticated()
    {
        $amount = random_int(1, 10);

        $response = $this->json('get', 'api/random_quotes/' . $amount);

        $response->assertStatus(401);
    }

    public function test_get_random_quotes_wrong_param()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('get', 'api/random_quotes/' . "string_value");

        $response->assertStatus(500);
    }

    public function test_delete_quote()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $quote = Quote::factory()->create([
            "user_id" => $user->id,
        ]);

        $response = $this->json('delete', 'api/quote/' . $quote->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $quote->id,
        ]);
        $response->assertJsonStructure([
            "id",
            "user_id",
            "quote",
            "updated_at",
            "created_at"
        ]);
    }

    public function test_delete_quote_nonexsistent()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('delete', 'api/quote/' . 1);

        $response->assertStatus(404);
    }

    public function test_delete_quote_unauthenticated()
    {
        $response = $this->json('delete', 'api/quote/' . 1);
        $response->assertStatus(401);
    }

    public function test_delete_quote_not_owned()
    {
        $userPrimary = User::factory(1)->create()->first();
        $userSecondary = User::factory(1)->create()->first();

        Sanctum::actingAs($userPrimary, ['*']);

        $quote = Quote::factory()->create([
            "user_id" => $userSecondary->id,
        ]);

        $response = $this->json('delete', 'api/quote/' . $quote->id);

        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => "You can't remove a Quote from someone else."
        ]);
    }

    public function test_get_favorite_quotes()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $amount = random_int(1, 10);
        Quote::factory($amount)->create([
            "user_id" => $user->id,
        ]);

        $response = $this->json('get', 'api/quotes/');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'quote', 'user_id', 'updated_at', 'created_at'] 
        ]);
        $response->assertJsonCount($amount);
    }

    public function test_get_favorite_quotes_unauthenticated()
    {
        $user = User::factory()->create();
        $amount = random_int(1, 10);
        Quote::factory($amount)->create([
            "user_id" => $user->id,
        ]);

        $response = $this->json('get', 'api/quotes/');
        $response->assertStatus(401);

    }

}
