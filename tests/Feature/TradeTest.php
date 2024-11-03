<?php

namespace Tests\Feature;

use App\Models\Elephant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TradeTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        Parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function user_can_trade_own_elephant_for_elephant_from_another_user()
    {
        Sanctum::actingAs($this->user);
        $otherUser = User::factory()->create();

        $elephant = Elephant::factory()->create(['name' => 'Red Elephant', 'user_id' => $this->user->id]);
        $elephant2 = Elephant::factory()->create(['name' => 'Blue Elephant', 'user_id' => $otherUser->id]);

        $response = $this->postJson('/api/trade', [
            'offer_elephant_id' => $elephant->id,
            'target_elephant_id' => $elephant2->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('elephants', [
            'id'      => $elephant->id,
            'user_id' => $otherUser->id,
        ]);

        $this->assertDatabaseHas('elephants', [
            'id'      => $elephant2->id,
            'user_id' => $this->user->id,
        ]);
    }
}
