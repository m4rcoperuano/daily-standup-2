<?php

namespace App\Http\Controllers;

use App\Http\Resources\StandUpGroupResource;
use App\Models\StandUpGroup;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StandUpGroupController extends Controller
{
    public function index(Request $request) {
        $this->authorize('viewAny', StandUpGroup::class);
        $user = $request->user();
        $canCreateOrEdit = $user->can('create', [StandUpGroup::class, $request->user()->currentTeam]);

        $groups = $request->user()->currentTeam
            ->standUpGroups()
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('StandUpGroups/Index')
            ->with('canCreateOrEdit', $canCreateOrEdit)
            ->with('standUpGroups', $groups);
    }

    public function create(Request $request)
    {
        $this->authorize('create', [StandUpGroup::class, $request->user()->currentTeam]);

        return Inertia::render('StandUpGroups/Create')
            ->with('team', $request->user()->currentTeam);
    }

    public function edit(Request $request, StandUpGroup $standUpGroup)
    {
        $this->authorize('update', [$standUpGroup, $request->user()->currentTeam]);
        $hasJiraIntegration = $request->user()->hasIntegration('atlassian');
        return Inertia::render('StandUpGroups/Edit')
            ->with("hasJiraIntegration", $hasJiraIntegration)
            ->with('standUpGroup', new StandUpGroupResource($standUpGroup));
    }

    public function store(Request $request)
    {
        $this->authorize('create', [StandUpGroup::class, $request->user()->currentTeam]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'atlassian_sprint_id' => 'nullable',
            'atlassian_board_id' => 'nullable'
        ]);

        $request->user()->currentTeam->standUpGroups()->create($validated);

        return redirect()->route('stand-up-groups.index');
    }

    public function update(Request $request, StandUpGroup $standUpGroup)
    {
        $this->authorize('update', [$standUpGroup, $request->user()->currentTeam]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'atlassian_sprint_id' => 'nullable',
            'atlassian_board_id' => 'nullable'
        ]);

        $standUpGroup->update($validated);

        return redirect()->route('stand-up-groups.index');
    }

    public function show(Request $request, StandUpGroup $standUpGroup)
    {
        $this->authorize('view', $standUpGroup);
        return Inertia::render('StandUpGroups/Show')
            ->with('standUpGroup', new StandUpGroupResource($standUpGroup));
    }

}
