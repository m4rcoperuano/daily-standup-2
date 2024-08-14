<?php

namespace App\Actions\LinkPreviews;

interface FetchServicePreview
{
    public function execute(string $url) : LinkPreviewDto;
}
