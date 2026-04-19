<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StandUpGroupResource;
use App\Models\StandUpGroup;
use Illuminate\Http\Request;

class StandUpGroupController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', StandUpGroup::class);

        $groups = $request->user()->currentTeam
            ->standUpGroups()
            ->orderByDesc('created_at')
            ->get();

        return StandUpGroupResource::collection($groups);
    }

    public function show(Request $request, StandUpGroup $standUpGroup)
    {
        $this->authorize('view', $standUpGroup);

        return new StandUpGroupResource($standUpGroup);
    }

    public function store(Request $request)
    {
        $team = $request->user()->currentTeam;

        $this->authorize('create', [StandUpGroup::class, $team]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'atlassian_sprint_id' => 'nullable|string',
            'atlassian_board_id' => 'nullable|string',
        ]);

        $group = $team->standUpGroups()->create($validated);

        return (new StandUpGroupResource($group))->response()->setStatusCode(201);
    }

    public function update(Request $request, StandUpGroup $standUpGroup)
    {
        $this->authorize('update', [$standUpGroup, $request->user()->currentTeam]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'atlassian_sprint_id' => 'nullable|string',
            'atlassian_board_id' => 'nullable|string',
        ]);

        $standUpGroup->update($validated);

        return new StandUpGroupResource($standUpGroup);
    }
}
