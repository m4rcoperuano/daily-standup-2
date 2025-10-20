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
        $start = Carbon::parse($date)->startOfDay()->toDateString();
        $end = Carbon::parse($date)->endOfDay()->toDateString();

        $result = Http::withToken(config('services.clockwork.api_key'))
            ->withQueryParameters([
                'starting_at' => $start,
                'ending_at' => $end,
                "user_query[]" => $email,
                "expand" => "worklogs,issues"
            ])
            ->get("https://api.clockwork.report/v1/worklogs");

        return $result->json();
    }
}
