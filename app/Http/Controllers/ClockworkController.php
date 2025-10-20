<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class ClockworkController extends Controller
{
    public function index(string $email, Request $request)
    {
        $date = $request->query('date');
        $team = $request->user()->currentTeam;
        $start = Carbon::parse($date)->startOfDay()->toDateString();
        $end = Carbon::parse($date)->endOfDay()->toDateString();

        $result = Http::withToken($team->clockwork_api_key)
            ->withQueryParameters([
                'starting_at' => $start,
                'ending_at' => $end,
                "user_query[]" => $email,
                "expand" => "worklogs,issues"
            ])
            ->get("https://api.clockwork.report/v1/worklogs");

        return $result->json();
    }

    public function settings(Request $request)
    {
        //I want to return a clockwork_api_key that has been encrypted for the current team

        $encryptedKey = encrypt($request->user()->currentTeam->clockwork_api_key);

        return response()->json([
            'clockwork_api_key' => $encryptedKey,
        ]);
    }

    public function updateSettings(Request $request) {
        $user = $request->user();
        $user->currentTeam()->update([
            'clockwork_api_key' => $request->clockwork_api_key,
        ]);
    }
}
