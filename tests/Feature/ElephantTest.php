<?php

namespace Tests\Feature;

use App\Models\Elephant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ElephantTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        Parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function elephants_can_be_retrieved(): void
    {
        Sanctum::actingAs($this->user);

        Elephant::factory()->count(10)->create();

        $response = $this->getJson('/api/elephants');

        $response->assertStatus(200);
        $response->assertJsonCount(10);
    }

    #[Test]
    public function elephants_can_be_looked_up_by_name(): void
    {
        Sanctum::actingAs($this->user);

        Elephant::factory()->create(['name' => 'Red Elephant']);
        Elephant::factory()->create(['name' => 'Blue Elephant']);

        $response = $this->getJson('/api/elephants/search?query=Red');

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Red Elephant']);
        $response->assertJsonMissing(['name' => 'Blue Elephant']);
    }

    #[Test]
    public function elephants_can_be_looked_up_by_description(): void
    {
        Sanctum::actingAs($this->user);

        Elephant::factory()->create(['description' => 'A Red elephant.']);
        Elephant::factory()->create(['description' => 'A Blue elephant.']);

        $response = $this->getJson('/api/elephants/search?query=Red');

        $response->assertStatus(200);
        $response->assertJsonFragment(['description' => 'A Red elephant.']);
        $response->assertJsonMissing(['description' => 'A Blue elephant.']);
    }
}
