<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Data de expiração no formato ISO
     *
     * @param  string  $value
     * @return string
     */
    public function getExpiredAtAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->toISOString();
    }

    public function setExpiredAtAttribute($value)
    {
        $this->attributes['expired_at'] = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $value);
    }

    /**
     * Scope da query para retornar as urls vencidas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasExpired($query)
    {
        return $query->where('expired_at', '<', now());
    }

    /**
     * Scope da query para retornar as urls não vencidas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasNotExpired($query)
    {
        return $query->where('expired_at', '>', now());
    }

    /**
     * Persiste uma ShortUrl e salva o link curto caso precise
     *
     * @param  array  $data
     * @return ShortUrl
     */
    public static function shorten(array $data): ShortUrl
    {
        $shortUrl = self::create($data);

        if (empty($shortUrl->shortened)) {
            $shortUrl->shortened = self::toBase($shortUrl->id);
            $shortUrl->save();
        }

        return $shortUrl;
    }

    /**
     * Verifica se a model ShortUrl está vencida.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expired_at < now();
    }

    /**
     * Transforma um número base10 em baseN (padrão 62)
     *
     * @param  int  $toEncode
     * @param  int  $base
     * @return string
     */
    public static function toBase(int $toEncode, int $base = 62): string
    {
        $dictionary = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $position = $toEncode  % $base;
        $encoded = $dictionary[$position];
        $next = floor($toEncode / $base);
        while ($next) {
            $position = $next % $base;
            $next = floor($next / $base);
            $encoded = "{$dictionary[$position]}{$encoded}";
        }

        return $encoded;
    }
}
