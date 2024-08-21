<?php

namespace App\Http\Controllers;

use App\Http\Requests\JiraRequest;
use App\Services\AtlassianIntegration;

class JiraController extends Controller
{
    public function boards(JiraRequest $request) {
        $integration = AtlassianIntegration::make($request->user());
        $resourceId = data_get($integration->getAccessibleResources(), "0.id");
        return $integration->getBoards($resourceId)->json('values');
    }

    public function sprints(JiraRequest $request, string $boardId) {
        $integration = AtlassianIntegration::make($request->user());
        $resourceId = data_get($integration->getAccessibleResources(), "0.id");
        return $integration->getSprints($resourceId, $boardId)->json('values');
    }
}
