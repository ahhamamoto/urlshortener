<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShortUrlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'original_url' => $this->original_url,
            'shortened' => $this->shortened,
            'full_shortened' => url($this->shortened),
            'created_at' => $this->created_at,
            'expired_at' => $this->expired_at,
        ];
    }
}
