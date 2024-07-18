<?php

namespace App\Http\Controllers;

use App\Http\Resources\StandUpEntryResource;
use App\Models\StandUpEntry;
use App\Models\StandUpGroup;
use Illuminate\Http\Request;

class StandUpEntryController extends Controller
{
    public function index(Request $request, StandUpGroup $standUpGroup)
    {
        $entries = $standUpGroup->standUpEntries()
            ->orderBy('date', 'desc')
            ->with('user')
            ->get();

        return StandUpEntryResource::collection($entries);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'in_progress' => 'nullable|string|max:4000',
            'priorities' => 'nullable|string|max:4000',
            'blockers' => 'nullable|string|max:4000',
            'stand_up_group_id' => 'required|exists:stand_up_groups,id',
        ]);


        $entry = StandUpEntry::make($validated);
        $entry->standUpGroup()->associate($validated['stand_up_group_id']);
        $entry->user()->associate($request->user());
        $entry->save();

        //201 created
        return response()->json($entry, 201);
    }

    public function update(Request $request, StandUpEntry $standUpEntry)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'in_progress' => 'nullable|string|max:4000',
            'priorities' => 'nullable|string|max:4000',
            'blockers' => 'nullable|string|max:4000',
        ]);

        $standUpEntry->fill($validated);
        $standUpEntry->save();

        return new StandUpEntryResource($standUpEntry);
    }
}
