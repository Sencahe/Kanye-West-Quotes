<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Quote;
use Tests\TestCase;

class WebQuoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_favorite_quote()
    {
        $user = User::factory(1)->create()->first();
        Sanctum::actingAs($user, ['*']);

        $quote = Str::random(10);

        $response = $this->json("post",'/request/quote', [
            'quote' => $quote,
            "user_id" => $user->id
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(
            ["id", "quote", "user_id", "created_at", "updated_at"]
        );
        $responseBody = json_decode($response->getContent(), true);
        $this->assertTrue($responseBody["quote"] == $quote);
        $this->assertTrue($responseBody["user_id"] == $user->id);

    }

    public function test_add_favorite_quote_repeated()
    {
        $user = User::factory(1)->create()->first();
        Sanctum::actingAs($user, ['*']);

        $quote = Quote::factory(1)->create(
            ["user_id" => $user->id]
        )->first();
        $quoteRepeated = $quote->quote;

        $response = $this->json('post','/request/quote', [
            'quote' => $quoteRepeated,
            'user_id' => $user->id
        ]);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            "errors" => ["quote"]
        ]);

    }

    public function test_add_quote_without_user()
    {
        $user = User::factory(1)->create()->first();
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('post','/request/quote', [
            'quote' => Str::random(10)
        ]);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            "errors" => ["user_id"]
        ]);

    }

    public function test_add_quote_unauthenticated()
    {
        $user = User::factory(1)->create()->first();

        $response = $this->json('post','/request/quote', [
            'quote' => Str::random(10),
            'user_id'=> $user->id   
        ]);
        $response->assertStatus(401);
    }

    public function test_get_random_quotes()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $amount = random_int(1, 10);

        $response = $this->json('get','request/random_quotes/' . $amount);

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

        $response = $this->json('get','request/random_quotes/' . $amount);

        $response->assertStatus(401);

    }

    public function test_get_random_quotes_wrong_param()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        
        $amount = random_int(1, 10);

        $response = $this->json('get','request/random_quotes/' . "string_value");

        $response->assertStatus(500);
        $response->assertJsonStructure([
            "message"
        ]);

    }

    public function test_get_favorite_quotes()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $amount = random_int(1, 10);
        $quotes = Quote::factory($amount)->create([
            "user_id"=> $user->id,
        ]);
        
        $response = $this->json('get','request/quotes/');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'quote','user_id','updated_at','created_at'] //
        ]);     
        $response->assertJsonCount($amount);
    }

    public function test_get_favorite_quotes_unauthenticated()
    {
        $user = User::factory()->create();

        $amount = random_int(1, 10);
        $quotes = Quote::factory($amount)->create([
            "user_id"=> $user->id,
        ]);
        
        $response = $this->json('get','request/quotes/');
        $response->assertStatus(401);
    }

    public function test_delete_quote()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $quote = Quote::factory()->create([
            "user_id"=> $user->id,
        ]);

        $response = $this->json('delete','request/quote/' . $quote->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id'=> $quote->id,
        ]);
        $response->assertJsonStructure([
            "id",
            "user_id",
            "quote",
            "updated_at",
            "created_at"
        ]);
    }

    public function test_delete_quote_unauthenticated()
    {
        $user = User::factory()->create();

        $quote = Quote::factory()->create([
            "user_id"=> $user->id,
        ]);

        $response = $this->json('delete','request/quote/' . $quote->id);

        $response->assertStatus(401);
  
    }

    public function test_delete_quote_nonexsistent()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('delete','request/quote/' . 1);

        $response->assertStatus(404);
    }
}
