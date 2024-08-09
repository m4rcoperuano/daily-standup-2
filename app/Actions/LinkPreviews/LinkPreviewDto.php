<?php

namespace App\Actions\LinkPreviews;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class LinkPreviewDto implements Arrayable, JsonSerializable
{
    public function __construct(
        public string $title,
        public string $description,
        public string $image,
        public string $url,
    )
    {

    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'url' => $this->url,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
