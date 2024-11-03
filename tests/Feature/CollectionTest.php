<?php

namespace Tests\Feature;

use App\Models\Elephant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp() : void
    {
        Parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function elephants_can_be_added_to_a_collection() : void
    {
        Sanctum::actingAs($this->user);

        $elephant = Elephant::factory()->create(['name' => 'Red Elephant']);
        $elephant2 = Elephant::factory()->create(['name' => 'Blue Elephant']);

        $response = $this->postJson('/api/collection/add', [
            'elephants' => [
                ['id' => $elephant->id],
                ['id' => $elephant2->id],
            ],
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('elephants', [
            'id'      => $elephant->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('elephants', [
            'id'      => $elephant2->id,
            'user_id' => $this->user->id,
        ]);
    }

    #[Test]
    public function elephant_can_not_be_added_to_a_collection_if_it_is_already_in() : void
    {
        Sanctum::actingAs($this->user);

        $elephant = Elephant::factory()->create(['name' => 'Red Elephant', 'user_id' => $this->user->id]);
        $elephant2 = Elephant::factory()->create(['name' => 'Blue Elephant']);

        $response = $this->postJson('/api/collection/add', [
            'elephants' => [
                ['id' => $elephant->id],
                ['id' => $elephant2->id],
            ],
        ]);

        $response->assertStatus(207);

        $this->assertDatabaseHas('elephants', [
            'id'      => $elephant2->id,
            'user_id' => $this->user->id,
        ]);
    }

    #[Test]
    public function elephant_can_not_be_added_to_a_collection_if_it_is_already_in_a_different_collection() : void
    {
        Sanctum::actingAs($this->user);
        $otherUser = User::factory()->create();

        $elephant = Elephant::factory()->create(['name' => 'Red Elephant', 'user_id' => $otherUser->id]);
        $elephant2 = Elephant::factory()->create(['name' => 'Blue Elephant']);

        $response = $this->postJson('/api/collection/add', [
            'elephants' => [
                ['id' => $elephant->id],
                ['id' => $elephant2->id],
            ],
        ]);

        $response->assertStatus(207);

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
