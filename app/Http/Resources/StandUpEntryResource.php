<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StandUpEntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'in_progress' => $this->in_progress,
            'priorities' => $this->priorities,
            'blockers' => $this->blockers,
            'stand_up_group_id' => $this->stand_up_group_id,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'profile_photo_url' => $this->user->profile_photo_url,
            ]),
            'links' => $this->getLinksFromText($this->in_progress.' '.$this->priorities.' '.$this->blockers),
            'updated_at' => $this->updated_at,
        ];
    }

    protected function getLinksFromText(string $htmlText): array {
        //regex for anchor tags with hrefs
        preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $htmlText, $matches);

        //only pluck the hrefs
        return collect($matches['href'])->map(fn($href) => $href)->toArray();
    }
}
