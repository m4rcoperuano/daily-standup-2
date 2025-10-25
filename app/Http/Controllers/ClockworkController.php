<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClockworkController extends Controller
{
    public function index(string $email, Request $request)
    {
        $date = $request->query('date');
        $carbonDate = CarbonImmutable::parse($date);
        $weekday = $carbonDate->dayOfWeekIso; // 1=Mon, 7=Sun

        // Determine the correct date to use for the query
        if ($weekday === 1) { // Monday
            $queryDate = $carbonDate->subDays(3); // previous Friday
        } elseif ($weekday === 6) { // Saturday
            $queryDate = $carbonDate->subDays(1); // previous Friday
        } elseif ($weekday === 7) { // Sunday
            $queryDate = $carbonDate->subDays(2); // previous Friday
        } else {
            $queryDate = $carbonDate->subDay(); // previous day
        }

        $start = $queryDate->startOfDay()->toDateString();
        $end = $queryDate->endOfDay()->toDateString();
        $team = $request->user()->currentTeam;

        $result = Http::withToken($team->clockwork_api_key)
            ->withQueryParameters([
                'starting_at' => $start,
                'ending_at' => $end,
                "user_query[]" => $email,
                "expand" => "worklogs,issues"
            ])
            ->get("https://api.clockwork.report/v1/worklogs");

        // Extract baseUrl from the self link in the first worklog entry, if available
        $baseUrl = null;
        $resultJson = $result->json();
        if (!empty($resultJson) && !empty($resultJson[0]['self'])) {
            if (preg_match('/^(https:\/\/[^\/]+\.atlassian\.net)/', $resultJson[0]['self'], $matches)) {
                $baseUrl = $matches[1];
            }
        }

        return response()->json([
            'date_used' => $queryDate->toDateString(),
            'base_url' => $baseUrl,
            'data' => $resultJson,
        ]);
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
