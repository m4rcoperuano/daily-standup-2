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
            'stand_up_entry_links' => $this->whenLoaded(
                'standUpEntryLinks',
                fn() => StandUpEntryLinkResource::collection($this->standUpEntryLinks)
            ),
            'updated_at' => $this->updated_at,
        ];
    }
}
