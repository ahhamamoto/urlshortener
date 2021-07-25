<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SessionAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_user_can_login()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Session started',
        ]);
    }

    /** @test */
    public function an_user_cannot_login_with_wrong_credentials()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'passwords',
        ]);

        $response->assertStatus(401);
        $response->assertJsonFragment([
            'message' => 'Invalid login details',
        ]);
    }

    /** @test */
    public function an_user_can_logout()
    {
        $response = $this->postJson('/logout');

        $response->assertStatus(204);
    }
}
