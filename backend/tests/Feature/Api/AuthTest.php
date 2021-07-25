<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_user_can_get_an_access_token()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/token', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseCount('personal_access_tokens', 1);
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }

    /** @test */
    public function an_user_cannot_get_an_access_token_with_wrong_cretentials()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/token', [
            'email' => $user->email,
            'password' => 'passwords',
        ]);

        $response->assertStatus(401);
        $this->assertDatabaseCount('personal_access_tokens', 0);
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }

    /** @test */
    public function an_user_can_get_user_data()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['bearer']);
        $response = $this->getJson('/api/user');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /** @test */
    public function an_user_cannot_get_user_data_without_credentials()
    {
        User::factory()->create();
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }
}
