<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Kevin Arts',
            'email' => 'kevinarts@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'kevinarts@example.com',
        ]);
    }

    #[Test]
    public function user_can_login()
    {
        $user = User::factory()->create([
            'email'    => 'kevinarts@example.com',
            'password' => bcrypt('password'), // Always hash the password
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'kevinarts@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function user_can_logout()
    {
        $user = User::factory()->create([
            'email'    => 'kevinarts@example.com',
            'password' => bcrypt('password'),
        ]);

        Sanctum::actingAs($user);

        $response = $this->post('/api/logout');

        $response->assertStatus(200);

        $this->assertCount(0, $user->tokens);
    }
}
