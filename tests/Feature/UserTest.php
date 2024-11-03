<?php

namespace Tests\Feature;

use App\Models\Elephant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        Parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function user_can_see_all_users_except_himself(): void
    {
        Sanctum::actingAs($this->user);

        User::factory()->count(10)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200);
        $response->assertJsonCount(10);
        $response->assertJsonMissing(['email' => $this->user->email]);
    }
}
