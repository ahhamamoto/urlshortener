<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RedirectShortenedController extends Controller
{
    /**
     * Redireciona para o link original (404 se não existir ou estiver vencido).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $shortened
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, string $shortened)
    {
        $shortUrl = Cache::remember($shortened, 600, function () use ($shortened) {
            return ShortUrl::where('shortened', $shortened)->first();
        });

        if (is_null($shortUrl) || $shortUrl->isExpired()) {
            Cache::forget($shortened);
            if (!is_null($shortUrl)) {
                $shortUrl->delete();
            }

            abort(404);
        }

        return redirect($shortUrl->original_url);
    }
}
