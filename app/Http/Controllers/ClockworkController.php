<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClockworkController extends Controller
{
    public function index(string $email, Request $request)
    {
        $date = $request->date('date');
        $start = $date->startOfDay()->toDateString();
        $end = $date->endOfDay()->toDateString();
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
            'date_used' => $date->toDateString(),
            'base_url' => $baseUrl,
            'data' => $resultJson,
        ]);
    }

    public function settings(Request $request)
    {
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

    public function hasIntegration(Request $request) {
        $team = $request->user()->currentTeam;
        return response()->json([
            'has_integration' => !empty($team->clockwork_api_key),
        ]);
    }
}
