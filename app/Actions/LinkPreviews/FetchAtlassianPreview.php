<?php

namespace App\Actions\LinkPreviews;

use App\Services\AtlassianIntegration;

class FetchAtlassianPreview implements FetchServicePreview
{
    public function __construct(protected AtlassianIntegration $integration)
    {}

    public function execute(string $url) : LinkPreviewDto {
        $urlParts = parse_url($url);
        $path = substr($urlParts['path'], 1); // remove leading slash
        $paths = explode('/', $path);
        $resource = $paths[0] ?? null;
        $host = $urlParts['host'];

        $cloudIds = [];
        if ($meta = $this->integration->socialiteIntegration->meta) {
            $cloudIds = $meta['resources'] ?? [];
        }
        else {
            $cloudIds = $this->integration->getAccessibleResources();
        }

        $cloud = collect($cloudIds)->where('url', "https://$host")->first();
        $cloudId = $cloud['id'] ?? null;

        return match($resource) {
            'browse' => $this->fetchJiraTicket($url, $cloudId),
            'wiki' => $this->fetchConfluencePage($url, $cloudId),
            default => app(FetchHtmlPreview::class)->execute($url),
        };
    }

    private function fetchJiraTicket(string $url, string $cloudId): LinkPreviewDto
    {
        $urlParts = parse_url($url);
        $path = substr($urlParts['path'], 1); // remove leading slash

        $paths = explode('/', $path);
        $resourceId = $paths[1] ?? null;

        $ticket = $this->integration->getJiraTicket($cloudId, $resourceId);

        return new LinkPreviewDto(
            title: $ticket->json('fields.summary'),
            description: '',
            image: $ticket->json('fields.issuetype.iconUrl'),
            url: $url,
        );
    }

    private function fetchConfluencePage(string $url, mixed $cloudId): LinkPreviewDto
    {
        $urlParts = parse_url($url);

        $pageId = null;
        if ($query = $urlParts['query'] ?? null) {
            parse_str($query, $queryParts);

            if (isset($queryParts['homepageId'])) {
                $pageId = $queryParts['homepageId'];
            }
        }
        else {
            $path = substr($urlParts['path'], 1); // remove leading slash
            $paths = explode('/', $path);
            $pageId = $paths[count($paths) - 2];
        }

        $page = $this->integration->getConfluencePage($cloudId, $pageId);

        return new LinkPreviewDto(
            title: $page->json('title'),
            description: '',
            image: 'https://dac-static.atlassian.com/favicon.ico',
            url: $url,
        );
    }
}
