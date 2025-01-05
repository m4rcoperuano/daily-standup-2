<?php

namespace App\Http\Controllers;

use App\Events\RemovedVote;
use App\Events\ResetVotes;
use App\Events\Voted;
use App\Events\VotesRevealed;
use App\Models\PointingRoom;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PointingRoomController extends Controller
{
    public function index(Request $request)
    {
        $room = $request->user()->currentTeam->pointingRooms()->with('votes')->firstOrFail();
        return Inertia::render('PointingRoom/Index')
            ->with('room', $room);
    }

    public function submitVote(Request $request)
    {
        /** @var PointingRoom $room */
        $room = $request->user()->currentTeam->pointingRooms()->firstOrFail();
        $vote = $room->votes()->updateOrCreate(
            ['user_id' => $request->user()->getKey()],
            ['value' => $request->input('vote')]
        );

        Voted::dispatch($vote);

        return response()->json($vote, 201);
    }

    public function deleteVote(Request $request)
    {
        /** @var PointingRoom $room */
        $room = $request->user()->currentTeam->pointingRooms()->firstOrFail();
        $vote = $room->votes()->where('user_id', $request->user()->getKey())->first();

        if ($vote) {
            RemovedVote::dispatch($vote->getKey(), $room->getKey());
            $vote->delete();
        }

        return response()->json(['message' => 'Vote deleted']);
    }

    public function revealVotes(Request $request)
    {
        /** @var PointingRoom $room */
        $room = $request->user()->currentTeam->pointingRooms()->firstOrFail();
        $room->update(['reveal' => true]);

        VotesRevealed::dispatch($room);

        return response()->json(['message' => 'Votes revealed']);
    }

    public function resetVotes(Request $request)
    {
        /** @var PointingRoom $room */
        $room = $request->user()->currentTeam->pointingRooms()->firstOrFail();
        $room->votes()->delete();
        $room->update(['reveal' => false]);

        ResetVotes::dispatch($room);

        return response()->json(['message' => 'Votes reset']);
    }
}
