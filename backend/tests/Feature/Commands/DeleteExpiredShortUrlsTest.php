<?php

namespace Tests\Feature\Commands;

use App\Models\ShortUrl;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteExpiredShortUrlsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function deletes_only_expired_short_urls()
    {
        ShortUrl::factory()->count(5)->create();
        $expired = ShortUrl::factory()->count(2)->create([
            'expired_at' => Carbon::yesterday()->toISOString(),
        ]);

        $this->artisan('shorturl:delete-expired')
            ->assertExitCode(0);

        $this->assertDatabaseCount('short_urls', 5);
        $this->assertDeleted($expired[0]);
        $this->assertDeleted($expired[1]);
    }

    /** @test */
    public function does_not_delete_unexpired_short_urls()
    {
        $unexpired = ShortUrl::factory()->count(2)->create();

        $this->artisan('shorturl:delete-expired')
            ->assertExitCode(0);

        $this->assertDatabaseCount('short_urls', 2);
        $this->assertDatabaseHas('short_urls', [
            'id' => $unexpired[0]->id,
        ]);
        $this->assertDatabaseHas('short_urls', [
            'id' => $unexpired[1]->id,
        ]);
    }
}
