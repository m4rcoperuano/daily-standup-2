<?php

namespace App\Http\Controllers;

use App\Http\Requests\JiraRequest;
use App\Services\AtlassianIntegration;

class JiraController extends Controller
{
    public function boards(JiraRequest $request) {
        $integration = AtlassianIntegration::make($request->user());
        $cloudId = data_get($integration->getAccessibleResources(), "0.id");
        return $integration->getBoards($cloudId)->json('values');
    }

    public function sprints(JiraRequest $request, string $boardId) {
        $integration = AtlassianIntegration::make($request->user());
        $cloudId = data_get($integration->getAccessibleResources(), "0.id");
        return $integration->getSprints($cloudId, $boardId)->json('values');
    }

    public function sprint(JiraRequest $request, string $sprintId) {
        $integration = AtlassianIntegration::make($request->user());
        $cloudId = data_get($integration->getAccessibleResources(), "0.id");
        return $integration->getSprint($cloudId, $sprintId)->json();
    }
}
