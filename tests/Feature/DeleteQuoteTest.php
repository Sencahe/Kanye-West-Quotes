<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;
use App\Models\Quote;


class DeleteQuoteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_quote()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $quote = Quote::factory()->create([
            "user_id"=> $user->id,
        ]);

        $response = $this->delete('api/quotes/' . $quote->id);

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
}
