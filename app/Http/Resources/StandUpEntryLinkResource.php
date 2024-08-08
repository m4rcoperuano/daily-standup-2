<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StandUpEntryLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'host' => $this->host,
            'text' => $this->text,
            'url' => $this->url,
            'attributes' => $this->attributes,
        ];
    }
}
