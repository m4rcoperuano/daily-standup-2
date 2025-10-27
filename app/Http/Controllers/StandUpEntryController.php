<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStandUpEntryRequest;
use App\Http\Requests\UpdateStandUpEntryRequest;
use App\Http\Resources\StandUpEntryResource;
use App\Models\StandUpEntry;
use App\Models\StandUpGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use League\HTMLToMarkdown\HtmlConverter;

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

    public function export(Request $request, StandUpGroup $standUpGroup)
    {
        $this->authorize('viewAny', [StandUpEntry::class, $standUpGroup]);

        $htmlConverter = new HTMLConverter();
        $htmlConverter->getConfig()->setOption('use_autolinks', false); // default

        $entries = $standUpGroup->standUpEntries()
            ->orderBy('date', 'desc')
            ->with('user', 'standUpEntryLinks')
            ->get()
            ->map(fn(StandUpEntry $entry) => [
                'Date' => $entry->date->toDateString(),
                'User' => $entry->user->name,
                'In Progress' => $htmlConverter->convert($entry->in_progress | ""),
                'Priorities' => $htmlConverter->convert($entry->priorities | ""),
                'Blockers' => $htmlConverter->convert($entry->blockers || ""),
            ]);

        $response = Http::withToken(config('services.openai.key'))
            ->timeout(120)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-5-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Create a summary of the following stand up entries. The audience is the business team, not the developer, so make it sound less technical and more company facing. Group it by Assignee then by Ticket.',
                    ],
                    [
                        'role' => 'user',
                        'content' => 'Here is the stand up entry data, reply in Markdown. Your reply must be grouped by assignee then by ticket with a 100-200 snippet of what they did. When grouping by ticket, just keep the hyperlink URL, no need for a prefix or suffix. Be succinct. ' . json_encode($entries),
                    ],
                ],
            ]);

        $data = $response->json();
        $summary = $data['choices'][0]['message']['content'] ?? 'No summary
    available.';
        return Str::markdown($summary);
    }
}
