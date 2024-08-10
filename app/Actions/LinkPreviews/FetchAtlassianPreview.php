<?php

namespace App\Actions\LinkPreviews;

use App\Models\SocialiteIntegration;
use App\Models\User;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
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

        //TODO: Save this in the db for the user
        $cloudIds = $this->getHttp($user)
            ->get('https://api.atlassian.com/oauth/token/accessible-resources')
            ->json();

        $cloud = collect($cloudIds)->where('url', "https://$host")->first();
        $cloudId = $cloud['id'] ?? null;

        return match($resource) {
            'browse' => $this->fetchJiraTicket($url, $cloudId, $user, $resourceId),
            'wiki' => $this->fetchConfluencePage($url, $cloudId, $user),
            default => app(FetchHtmlPreview::class)->execute($url),
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
        $integration = $user->socialiteIntegrations()
            ->where('provider', 'atlassian')->first();

        return Http::acceptJson()
            ->withToken($integration->access_token)
            ->retry(2, 0, function (Exception $exception, PendingRequest $request) use ($integration) {
            if (! $exception instanceof RequestException || $exception->response->status() !== 401) {
                return false;
            }

            $request->withToken($this->refreshToken($integration));

            return true;
        });
    }

    private function refreshToken(SocialiteIntegration $integration) : string {
        $result = Http::acceptJson()
            ->post('https://auth.atlassian.com/oauth/token', [
                'grant_type' => 'refresh_token',
                'client_id' => config('services.atlassian.client_id'),
                'client_secret' => config('services.atlassian.client_secret'),
                'refresh_token' => $integration->refresh_token,
            ]);

        $accessToken = $result->json('access_token');
        $refreshToken = $result->json('refresh_token');

        $integration->update([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ]);

        return $accessToken;
    }

    private function fetchConfluencePage(string $url, mixed $cloudId, User $user): LinkPreviewDto
    {
        $urlParts = parse_url($url);
        $path = substr($urlParts['path'], 1); // remove leading slash
        $paths = explode('/', $path);
        $pageId = $paths[count($paths) - 2];

        $page = $this->getHttp($user)->get("https://api.atlassian.com/ex/confluence/$cloudId/wiki/api/v2/pages/$pageId");

        return new LinkPreviewDto(
            title: $page->json('title'),
            description: '',
            image: 'https://dac-static.atlassian.com/favicon.ico',
            url: $url,
        );
    }
}
