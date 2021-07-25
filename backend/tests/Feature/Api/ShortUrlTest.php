<?php

namespace Tests\Feature\Api;

use App\Models\ShortUrl;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_shortened_url()
    {
        $response = $this->postJson('/api/short-url', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('short_urls', [
            'original_url' => 'https://example.com',
        ]);
    }

    /** @test */
    public function can_create_shortened_url_with_custom_shortened()
    {
        $response = $this->postJson('/api/short-url', [
            'original_url' => 'https://example.com',
            'shortened' => 'example',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('short_urls', [
            'original_url' => 'https://example.com',
            'shortened' => 'example',
        ]);
    }

    /** @test */
    public function can_create_shortened_url_with_custom_expiration()
    {
        $expiredAt = Carbon::tomorrow();
        $response = $this->postJson('/api/short-url', [
            'original_url' => 'https://example.com',
            'expired_at' => $expiredAt->toISOString(),
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('short_urls', [
            'original_url' => 'https://example.com',
            'expired_at' => $expiredAt->format('Y-m-d H:i:s'),
        ]);
    }

    /** @test */
    public function cannot_create_shortened_url_without_original_url()
    {
        $response = $this->postJson('/api/short-url', []);

        $response->assertStatus(422);
        $this->assertDatabaseCount('short_urls', 0);
    }

    /** @test */
    public function cannot_create_shortened_url_with_custom_expiration_expired()
    {
        $expiredAt = Carbon::yesterday()->toISOString();
        $response = $this->postJson('/api/short-url', [
            'original_url' => 'https://example.com',
            'expired_at' => $expiredAt,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('short_urls', 0);
    }

    /** @test */
    public function cannot_create_shortened_url_with_classing_shortened()
    {
        ShortUrl::shorten(['original_url' => 'https://example.com', 'shortened' => 'example']);
        $response = $this->postJson('/api/short-url', [
            'original_url' => 'https://example.com',
            'shortened' => 'example',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('short_urls', 1);
    }

    /** @test */
    public function can_fetch_short_url_listing()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['bearer']
        );

        ShortUrl::factory()->count(70)->create();

        $response = $this->getJson('/api/short-url', []);

        $response->assertStatus(200);
        $response->assertJsonCount(25, $key = 'data');
        $response->assertJsonFragment([
            'current_page' => 1,
            'last_page' => 3,
            'per_page' => 25,
            'items_in_page' => 25,
            'total' => 70,
        ]);
    }

    /** @test */
    public function can_fetch_short_url_listing_second_page()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['bearer']
        );

        ShortUrl::factory()->count(70)->create();

        $response = $this->getJson('/api/short-url?page=3');

        $response->assertStatus(200);
        $response->assertJsonCount(20, $key = 'data');
        $response->assertJsonFragment([
            'current_page' => 3,
            'last_page' => 3,
            'per_page' => 25,
            'items_in_page' => 20,
            'total' => 70,
        ]);
    }

    /** @test */
    public function cannot_fetch_short_url_listing_unauthenticated()
    {
        $response = $this->getJson('/api/short-url');

        $response->assertStatus(401);
        $response->assertJsonFragment([
            'message' => 'Unauthenticated.'
        ]);
    }
}
