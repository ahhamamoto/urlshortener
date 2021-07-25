<?php

namespace Tests\Feature;

use App\Models\ShortUrl;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RedirectShortenedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function redirects_shortened_to_original_url()
    {
        $shortUrl = ShortUrl::shorten(['original_url' => 'https://example.com']);

        $response = $this->get("/{$shortUrl->shortened}");

        $response->assertStatus(302);
        $this->assertTrue(Cache::has($shortUrl->shortened));
    }

    /** @test */
    public function not_found_on_inexisting_shortened()
    {
        $response = $this->get('/test');

        $response->assertStatus(404);
    }

    /** @test */
    public function not_found_on_expired_link()
    {
        $shortUrl = ShortUrl::shorten([
            'original_url' => 'https://example.com',
            'expired_at' => Carbon::yesterday()->toISOString(),
        ]);

        $response = $this->get("/{$shortUrl->shortened}");

        $response->assertStatus(404);
        $this->assertFalse(Cache::has($shortUrl->shortened));
        $this->assertDatabaseMissing('short_urls', [
            'id' => $shortUrl->id,
        ]);
    }
}
