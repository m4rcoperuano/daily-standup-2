<?php

namespace App\Actions\LinkPreviews;

use App\Models\StandUpEntryLink;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class FetchPreview
{
    public function execute(User $user, StandUpEntryLink $link): LinkPreviewDto {
        $url = $link->url;
        $cacheKey = md5($url).$user->getKey();

        if (Cache::has($cacheKey)) {
          //  return Cache::get($cacheKey);
        }

        $host = $link->host; //may be dev.github.com
        if (str_contains($host, 'atlassian') && !str_contains($host, 'www.atlassian.com') ) {
            $host = 'atlassian.com';
        }
        else if (str_contains($host, 'github')) {
            $host = 'github.com';
        }

        $preview = match ($host) {
            'github.com' => app(FetchGithubPreview::class)->execute($user, $url),
            'atlassian.com' => app(FetchAtlassianPreview::class)->execute($user, $url),
            default => app(FetchHtmlPreview::class)->execute($url),
        };

        Cache::put($cacheKey, $preview, now()->addDay());

        return $preview;
    }
}
