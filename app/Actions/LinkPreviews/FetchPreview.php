<?php

namespace App\Actions\LinkPreviews;

use App\Models\StandUpEntryLink;
use App\Models\User;
use App\Services\AtlassianIntegration;
use App\Services\GithubIntegration;
use Illuminate\Support\Facades\Cache;

class FetchPreview
{
    public function execute(User $user, StandUpEntryLink $link): LinkPreviewDto {
        $url = $link->url;
        $cacheKey = md5($url).$user->getKey();

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $host = $link->host;
        $previewAction = $this->getPreviewService($host, $user);
        $preview = $previewAction->execute($url);

        Cache::put($cacheKey, $preview, now()->addDay());

        return $preview;
    }

    private function getPreviewService(string $host, User $user): FetchServicePreview
    {
        if (str_contains($host, 'atlassian') && !str_contains($host, 'www.atlassian.com') ) {
            return new FetchAtlassianPreview(AtlassianIntegration::make($user));
        }
        else if (str_contains($host, 'github')) {
            return new FetchGithubPreview(GithubIntegration::make($user));
        }

        return new FetchHtmlPreview();

    }
}
