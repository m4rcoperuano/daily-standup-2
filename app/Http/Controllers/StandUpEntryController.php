<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStandUpEntryRequest;
use App\Http\Requests\UpdateStandUpEntryRequest;
use App\Http\Resources\StandUpEntryResource;
use App\Models\StandUpEntry;
use App\Models\StandUpGroup;
use Illuminate\Http\Request;

class StandUpEntryController extends Controller
{
    public function index(Request $request, StandUpGroup $standUpGroup)
    {
        $this->authorize('viewAny', [StandUpEntry::class, $standUpGroup]);

        $entries = $standUpGroup->standUpEntries()
            ->orderBy('date', 'desc')
            ->with('user', 'standUpEntryLinks')
            ->unless($request->boolean('all'), fn($query) => $query->where('user_id', $request->user()->id))
            ->get();

        return StandUpEntryResource::collection($entries);
    }

    public function store(StoreStandUpEntryRequest $request)
    {
        $attributes = $request->validated();

        $entry = StandUpEntry::make($attributes);
        $entry->standUpGroup()->associate($attributes['stand_up_group_id']);
        $entry->user()->associate($request->user());
        $entry->save();

        $entry->load('user')->load('standUpEntryLinks');

        return (new StandUpEntryResource($entry))->response()->setStatusCode(201);
    }

    public function update(UpdateStandUpEntryRequest $request, StandUpEntry $standUpEntry)
    {
        $attributes = $request->validated();
        $standUpEntry->fill($attributes);
        $standUpEntry->save();

        $standUpEntry->load('user')->load('standUpEntryLinks');

        return new StandUpEntryResource($standUpEntry);
    }

    public function destroy(Request $request, StandUpEntry $standUpEntry)
    {
        $this->authorize('delete', $standUpEntry);
        $standUpEntry->delete();

        return response()->noContent();
    }
}
