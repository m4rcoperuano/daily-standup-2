<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StandUpGroupResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "atlassian_board_id" => $this->atlassian_board_id,
            "atlassian_sprint_id" => $this->atlassian_sprint_id,
            'team_id' => $this->team_id
        ];
    }
}
