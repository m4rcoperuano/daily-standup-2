<?php

namespace App\Actions\LinkPreviews;

use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class FetchGithubPreview
{
    public function execute(User $user, string $url) : LinkPreviewDto {
        try {
            $urlParts = parse_url($url);
            $path = substr($urlParts['path'], 1); // remove leading slash
            $paths = explode('/', $path);
            $repositoryOwner = $paths[0];
            $repositoryName = $paths[1];
            $resource = $paths[2] ?? null;
            $resourceId = $paths[3] ?? null;

            return match($resource) {
                'issues' => $resourceId ? $this->fetchIssue($url, $user, $repositoryOwner, $repositoryName, $resourceId)
                    : $this->fetchRepository($url, $user, $repositoryOwner, $repositoryName),
                'pull' => $this->fetchPullRequest($url, $user, $repositoryOwner, $repositoryName, $resourceId),
                default => $this->fetchRepository($url, $user, $repositoryOwner, $repositoryName),
            };
        }
        catch (\Exception $e) {
            return new LinkPreviewDto(
                title: 'Github Error ' . $e->getMessage(),
                description: '',
                image: 'https://github.githubassets.com/favicons/favicon.svg',
                url: $url,
            );
        }
    }

    private function getHttp(User $user): PendingRequest {
        $token = $user->socialiteIntegrations()->where('provider', 'github')->first()?->access_token;

        return Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
            'Authorization' => "Bearer $token",
            'X-GitHub-Api-Version' => '2022-11-28',
        ]);
    }

    private function fetchIssue(string $url, User $user, string $repositoryOwner, string $repositoryName, ?string $resourceId): LinkPreviewDto
    {
        $issue = $this->getHttp($user)->get("https://api.github.com/repos/$repositoryOwner/$repositoryName/issues/$resourceId");
        $title = $issue->json('title');
        $description = $issue->json('body');

        return new LinkPreviewDto(
            title: $title,
            description: $description,
            image: 'https://github.githubassets.com/favicons/favicon.svg',
            url: $url,
        );
    }

    private function fetchPullRequest(string $url, User $user, string $repositoryOwner, string $repositoryName, ?string $resourceId): LinkPreviewDto
    {
        $pr = $this->getHttp($user)->get("https://api.github.com/repos/$repositoryOwner/$repositoryName/pulls/$resourceId");
        $title = $pr->json('title');
        $description = $pr->json('body');

        return new LinkPreviewDto(
            title: $title,
            description: $description,
            image: 'https://github.githubassets.com/favicons/favicon.svg',
            url: $url,
        );
    }

    private function fetchRepository(string $url, User $user, string $repositoryOwner, string $repositoryName): LinkPreviewDto
    {
        $repo = $this->getHttp($user)->get("https://api.github.com/repos/$repositoryOwner/$repositoryName");
        $title = $repo->json('name');

        return new LinkPreviewDto(
            title: $title,
            description: '',
            image: 'https://github.githubassets.com/favicons/favicon.svg',
            url: $url,
        );
    }
}
