<?php

namespace App\Services;

use App\Models\SocialiteIntegration;
use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GithubIntegration
{
    public function __construct(
        protected SocialiteIntegration $integration
    )
    {
    }

    public static function make(User $user): self {
        $socialiteIntegration = $user->socialiteIntegrations()->where('provider', 'github')->first();
        return new self($socialiteIntegration);
    }

    public function getRepository(string $repositoryOwner, string $repositoryName): Response
    {
        return $this->http()->get("/repos/$repositoryOwner/$repositoryName");
    }

    public function getPullRequest(string $repositoryOwner, string $repositoryName, string $resourceId): Response
    {
        return $this->http()->get("/repos/$repositoryOwner/$repositoryName/pulls/$resourceId");
    }

    public function getIssue(string $repositoryOwner, string $repositoryName, string $resourceId): Response
    {
        return $this->http()->get("/repos/$repositoryOwner/$repositoryName/issues/$resourceId");
    }

    private function http(): PendingRequest {
        return Http::acceptJson()
            ->baseUrl("https://api.github.com")
            ->withToken($this->integration->access_token)
            ->withHeaders([
                'X-GitHub-Api-Version' => '2022-11-28',
            ]);
    }
}
