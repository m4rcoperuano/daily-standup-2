<?php

namespace App\Services;

use App\Models\SocialiteIntegration;
use App\Models\User;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class AtlassianIntegration
{
    public function __construct(
        protected SocialiteIntegration $integration
    )
    {
    }

    public static function make(User $user): self
    {
        $socialiteIntegration = $user->socialiteIntegrations()->where('provider', 'atlassian')->first();
        return new self($socialiteIntegration);
    }

    public function getConfluencePage(string $cloudId, string $pageId): Response
    {
        return $this
            ->http()
            ->get("/ex/confluence/$cloudId/wiki/api/v2/pages/$pageId");
    }

    public function getJiraTicket(string $cloudId, string $ticketId): Response
    {
        return $this
            ->http()
            ->get("/ex/jira/$cloudId/rest/api/3/issue/$ticketId");
    }

    public function getAccessibleResources(): array
    {
        return $this
            ->http()
            ->get('/oauth/token/accessible-resources')
            ->json();
    }

    private function http(): PendingRequest {
        return Http::acceptJson()
            ->baseUrl("https://api.atlassian.com")
            ->withToken($this->integration->access_token)
            ->retry(2, 0, function (Exception $exception, PendingRequest $request) {
                if (! $exception instanceof RequestException || $exception->response->status() !== 401) {
                    return false;
                }

                $request->withToken($this->refreshToken());

                return true;
            });
    }

    private function refreshToken() : string {
        $result = Http::acceptJson()
            ->post('https://auth.atlassian.com/oauth/token', [
                'grant_type' => 'refresh_token',
                'client_id' => config('services.atlassian.client_id'),
                'client_secret' => config('services.atlassian.client_secret'),
                'refresh_token' => $this->integration->refresh_token,
            ]);

        $accessToken = $result->json('access_token');
        $refreshToken = $result->json('refresh_token');

        $this->integration->update([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ]);

        return $accessToken;
    }
}
