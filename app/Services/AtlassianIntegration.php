<?php

namespace App\Services;

use App\Exceptions\IntegrationMissing;
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
        public SocialiteIntegration $socialiteIntegration
    )
    {
    }

    public static function make(User $user): self
    {
        $socialiteIntegration = $user->socialiteIntegrations()->where('provider', 'atlassian')->first();
        if (!$socialiteIntegration) {
            throw new IntegrationMissing("atlassian", "Integration for atlassian not set up");
        }
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

    public function getJiraSprint(string $cloudId, string $sprintId): Response
    {
        return $this
            ->http()
            ->get("/ex/jira/$cloudId/rest/agile/1.0/sprint/$sprintId");
    }

    public function getBoards(string $cloudId): array
    {
        $response = $this
            ->http()
            ->get("/ex/jira/$cloudId/rest/agile/1.0/board");

        return $response->json('values', []);
    }

    public function getSprints(string $cloudId, string $boardId, bool $activeOnly = true): array
    {
        $url = "/ex/jira/$cloudId/rest/agile/1.0/board/$boardId/sprint";
        $queryParams = [];

        if ($activeOnly) {
            $queryParams['state'] = 'active,future';
        }

        $response = $this
            ->http()
            ->get($url, $queryParams);

        $values = $response->json('values', []);
        $isLast = $response->json('isLast', true);

        while (!$isLast) {
            $startAt = count($values);
            $nextResponse = $this
                ->http()
                ->get($url, array_merge($queryParams, ['startAt' => $startAt]));

            $values = array_merge($values, $nextResponse->json('values', []));
            $isLast = $nextResponse->json('isLast', true);
        }

        return $values;
    }

    public function getSprint(string $cloudId, string $sprintId): Response
    {
        return $this
            ->http()
            ->get("/ex/jira/$cloudId/rest/agile/1.0/sprint/$sprintId");
    }

    public function getAccessibleResources(): array
    {
        if ($meta = $this->socialiteIntegration->meta) {
            if (isset($meta['resources'])) {
                return $meta['resources'];
            }
        }

        $resources = $this
            ->http()
            ->get('/oauth/token/accessible-resources')
            ->json();

        $this->socialiteIntegration->update([
            'meta' => ['resources' => $resources],
        ]);

        return $resources;
    }

    public function getActivity(string $cloudId): Response
    {
        return $this->http()->get("/ex/jira/$cloudId/rest/api/3/events");
    }

    private function http(): PendingRequest {
        return Http::acceptJson()
            ->baseUrl("https://api.atlassian.com")
            ->withToken($this->socialiteIntegration->access_token)
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
                'refresh_token' => $this->socialiteIntegration->refresh_token,
            ]);

        $accessToken = $result->json('access_token');
        $refreshToken = $result->json('refresh_token');

        $this->socialiteIntegration->update([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ]);

        return $accessToken;
    }
}
