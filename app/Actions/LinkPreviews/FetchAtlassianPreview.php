<?php

namespace App\Actions\LinkPreviews;

use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class FetchAtlassianPreview
{
    public function execute(User $user, string $url) : LinkPreviewDto {
        $urlParts = parse_url($url);
        $path = substr($urlParts['path'], 1); // remove leading slash
        $paths = explode('/', $path);
        $resource = $paths[0] ?? null;
        $resourceId = $paths[1] ?? null;
        $host = $urlParts['host'];

        $cloudIds = $this->getHttp($user)
            ->get('https://api.atlassian.com/oauth/token/accessible-resources')
            ->json();

        $cloud = collect($cloudIds)->where('url', "https://$host")->first();
        $cloudId = $cloud['id'];

        return match($resource) {
            'browse' => $this->fetchJiraTicket($url, $cloudId, $user, $resourceId),
            default => $this->tryFetchingStill($url, $cloudId, $user, $resource, $resourceId),
        };
    }

    private function fetchJiraTicket(string $url, string $cloudId, User $user, ?string $resourceId): LinkPreviewDto
    {
        $ticket = $this->getHttp($user)->get("https://api.atlassian.com/ex/jira/{$cloudId}/rest/api/3/issue/$resourceId");

        return new LinkPreviewDto(
            title: $ticket->json('fields.summary'),
            description: '',
            image: $ticket->json('fields.issuetype.iconUrl'),
            url: $url,
        );
    }

    private function tryFetchingStill(string $url, string $cloudId, User $user, ?string $resource, ?string $resourceId): LinkPreviewDto
    {
        return new LinkPreviewDto(
            title: 'Atlassian',
            description: 'Atlassian',
            image: 'https://www.atlassian.com/favicon-32x32.png',
            url: $url,
        );
    }

    private function getHttp(User $user): PendingRequest {
        $token = $user->socialiteIntegrations()
            ->where('provider', 'atlassian')->first()?->access_token;

        return Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $token",
        ]);
    }
}
