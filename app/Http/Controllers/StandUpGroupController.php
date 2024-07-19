<?php

namespace App\Http\Controllers;

use App\Models\StandUpGroup;
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

    public function create()
    {
        return Inertia::render('StandUpGroups/Create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $request->user()->currentTeam->standUpGroups()->create([
            'name' => $request->name,
        ]);

        return redirect()->route('stand-up-groups.index');
    }

    public function show(Request $request, StandUpGroup $standUpGroup)
    {
        $this->authorize('view', $standUpGroup);
        return Inertia::render('StandUpGroups/Show')
            ->with('standUpGroup', $standUpGroup);
    }

}
