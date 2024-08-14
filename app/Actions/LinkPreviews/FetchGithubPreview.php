<?php

namespace App\Actions\LinkPreviews;

use App\Services\GithubIntegration;

class FetchGithubPreview implements FetchServicePreview
{
    public function __construct(protected GithubIntegration $integration)
    {}

    public function execute(string $url) : LinkPreviewDto {
        try {
            $urlParts = parse_url($url);
            $path = substr($urlParts['path'], 1); // remove leading slash
            $paths = explode('/', $path);
            $repositoryOwner = $paths[0];
            $repositoryName = $paths[1];
            $resource = $paths[2] ?? null;
            $resourceId = $paths[3] ?? null;

            return match($resource) {
                'issues' => $resourceId ? $this->fetchIssue($url, $repositoryOwner, $repositoryName, $resourceId)
                    : $this->fetchRepository($url, $repositoryOwner, $repositoryName),
                'pull' => $this->fetchPullRequest($url, $repositoryOwner, $repositoryName, $resourceId),
                default => $this->fetchRepository($url, $repositoryOwner, $repositoryName),
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

    private function fetchIssue(string $url, string $repositoryOwner, string $repositoryName, ?string $resourceId): LinkPreviewDto
    {
        $issue = $this->integration->getIssue($repositoryOwner, $repositoryName, $resourceId);
        $title = $issue->json('title');
        $description = $issue->json('body');

        return new LinkPreviewDto(
            title: $title,
            description: $description,
            image: 'https://github.githubassets.com/favicons/favicon.svg',
            url: $url,
        );
    }

    private function fetchPullRequest(string $url, string $repositoryOwner, string $repositoryName, ?string $resourceId): LinkPreviewDto
    {
        $pr = $this->integration->getPullRequest($repositoryOwner, $repositoryName, $resourceId);
        $title = $pr->json('title');
        $description = $pr->json('body');

        return new LinkPreviewDto(
            title: $title,
            description: $description,
            image: 'https://github.githubassets.com/favicons/favicon.svg',
            url: $url,
        );
    }

    private function fetchRepository(string $url, string $repositoryOwner, string $repositoryName): LinkPreviewDto
    {
        $repo = $this->integration->getRepository($repositoryOwner, $repositoryName);
        $title = $repo->json('name');

        return new LinkPreviewDto(
            title: $title,
            description: '',
            image: 'https://github.githubassets.com/favicons/favicon.svg',
            url: $url,
        );
    }
}
