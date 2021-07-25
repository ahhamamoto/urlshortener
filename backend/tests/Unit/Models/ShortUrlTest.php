<?php

namespace Tests\Unit\Models;

use App\Models\ShortUrl;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ShortUrlTest extends TestCase
{
   /** @test */
    public function yesterday_is_expired()
    {

        $shortUrl = new ShortUrl();
        $shortUrl->expired_at = Carbon::yesterday()->toISOString();

        $this->assertTrue($shortUrl->isExpired());
    }

    /** @test */
    public function tomorrow_is_not_expired()
    {

        $shortUrl = new ShortUrl();
        $shortUrl->expired_at = Carbon::tomorrow()->toISOString();

        $this->assertFalse($shortUrl->isExpired());
    }

    /** @test */
    public function base10_to_base62_conversions()
    {
        $pairsToAssert = [
            [1, '1'],
            [100, '1C'],
            [1000, 'g8'],
            [10000, '2Bi'],
            [100000, 'q0U'],
            [1000000, '4c92'],
            [10000000, 'FXsk'],
            [100000000, '6LAze'],
        ];

        foreach ($pairsToAssert as $pairToAssert) {
            $this->assertSame($pairToAssert[1], ShortUrl::toBase($pairToAssert[0]));
        }
    }
}
