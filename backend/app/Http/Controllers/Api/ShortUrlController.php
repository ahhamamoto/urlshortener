<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ShortUrlPostRequest;
use App\Http\Resources\ShortUrlCollection;
use App\Models\ShortUrl;
use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    protected $perPage = 25;

    /**
     * Retorna uma listagem de urls não vencidas com paginação.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\ShortUrlCollection
     */
    public function index(Request $request)
    {
        $shortUrls = ShortUrl::hasNotExpired()
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        return new ShortUrlCollection($shortUrls);
    }

    /**
     * Cria um novo url curta.
     *
     * @param  \App\Http\Requests\Api\ShortUrlPostRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ShortUrlPostRequest $request)
    {
        $shortUrl = ShortUrl::shorten($request->validated());

        return response()->json([
            'shortened_url' => url($shortUrl->shortened),
        ], 201);
    }
}
