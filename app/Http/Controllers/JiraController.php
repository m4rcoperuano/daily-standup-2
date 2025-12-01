<?php

namespace App\Http\Controllers;

use App\Http\Requests\JiraRequest;
use App\Services\AtlassianIntegration;
use Illuminate\Support\Facades\Cache;

class JiraController extends Controller
{
    public function boards(JiraRequest $request) {
        $integration = AtlassianIntegration::make($request->user());
        $cloudId = data_get($integration->getAccessibleResources(), "0.id");
        return $integration->getBoards($cloudId);
    }

    public function sprints(JiraRequest $request, string $boardId) {
        $integration = AtlassianIntegration::make($request->user());
        $cloudId = data_get($integration->getAccessibleResources(), "0.id");
        $active = $request->boolean('active');
        $cacheKey = "jira_sprints_{$boardId}_" . ($active ? 'active' : 'all');

        return Cache::remember(
            $cacheKey,
            60,
            fn() => $integration->getSprints($cloudId, $boardId, $active)
        );
    }

    public function sprint(JiraRequest $request, string $sprintId) {
        $integration = AtlassianIntegration::make($request->user());
        $cloudId = data_get($integration->getAccessibleResources(), "0.id");
        return $integration->getSprint($cloudId, $sprintId)->json();
    }
}
