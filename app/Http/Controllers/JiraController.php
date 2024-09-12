<?php

namespace App\Http\Controllers;

use App\Http\Requests\JiraRequest;
use App\Models\Team;
use App\Services\AtlassianIntegration;

class JiraController extends Controller
{
    public function boards(JiraRequest $request, Team $team) {
        $integration = AtlassianIntegration::make($request->user());
        $cloudId = data_get($integration->getAccessibleResources(), "0.id");
        return $integration->getBoards($cloudId)->json('values');
    }

    public function sprints(JiraRequest $request, Team $team, string $boardId) {
        $integration = AtlassianIntegration::make($request->user());
        $cloudId = data_get($integration->getAccessibleResources(), "0.id");
        return $integration->getSprints($cloudId, $boardId, $request->boolean('active'))->json('values');
    }

    public function sprint(JiraRequest $request, Team $team, string $sprintId) {
        $integration = AtlassianIntegration::make($request->user());
        $cloudId = data_get($integration->getAccessibleResources(), "0.id");
        return $integration->getSprint($cloudId, $sprintId)->json();
    }
}
