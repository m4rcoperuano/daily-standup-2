<?php

namespace App\Http\Controllers;

use App\Http\Resources\StandUpGroupResource;
use App\Http\Resources\UserResource;
use App\Models\StandUpGroup;
use App\Models\User;
use App\Services\AtlassianIntegration;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StandUpGroupController extends Controller
{
    public function index(Request $request) {
        $this->authorize('viewAny', StandUpGroup::class);

        $groups = $request->user()->currentTeam
            ->standUpGroups()
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('StandUpGroups/Index')
            ->with('standUpGroups', $groups);
    }

    public function create(Request $request)
    {
        $hasJiraIntegration = $request->user()->hasIntegration('atlassian');
        return Inertia::render('StandUpGroups/Create')
            ->with("hasJiraIntegration", $hasJiraIntegration);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'atlassian_sprint_id' => 'nullable',
            'atlassian_board_id' => 'nullable'
        ]);

        $request->user()->currentTeam->standUpGroups()->create($validated);

        return redirect()->route('stand-up-groups.index');
    }

    public function show(Request $request, StandUpGroup $standUpGroup)
    {
        $this->authorize('view', $standUpGroup);
        return Inertia::render('StandUpGroups/Show')
            ->with('standUpGroup', new StandUpGroupResource($standUpGroup));
    }

}
