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

    public function show(Request $request, StandUpEntry $standUpEntry)
    {
        $this->authorize('view', $standUpEntry);

        $standUpEntry->load('user', 'standUpEntryLinks');

        return new StandUpEntryResource($standUpEntry);
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
                'In Progress' => $htmlConverter->convert($entry->in_progress ?? ""),
                'Priorities' => $htmlConverter->convert($entry->priorities ?? ""),
                'Blockers' => $htmlConverter->convert($entry->blockers ?? ""),
            ]);

        $entriesTextFormatted = $entries->map(function ($entry) {
            return "Date: {$entry['Date']}\nUser: {$entry['User']}\nIn Progress:\n{$entry['In Progress']}\nPriorities:\n{$entry['Priorities']}\nBlockers:\n{$entry['Blockers']}\n";
        })->implode("\n---\n");

        $response = Http::withToken(config('services.openai.key'))
            ->timeout(120)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-5',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' =>
                            <<<PROMPT
                            '
                            YOUR ROLE: BUSINESS ANALYST.
                            YOUR TASKS:
                            1. Given the text from stand up entries, produce a concise summary for a business audience.
                            2. Group the summary by Assignee and then by Ticket.
                            3. Focus only on development work, excluding QA tasks.
                            4. Ensure the summary is clear, non-technical, and suitable for a Sprint Review meeting.

                            EXAMPLE:
                            ASSIGNEE: Jane Doe
                            - http://example.com/ticket-123: Implemented the user authentication module, enabling secure login and registration for users.
                            - http://example.com/ticket-456: Developed the payment processing feature, allowing users to make
                            purchases seamlessly.
                            ASSIGNEE: John Smith
                            - http://example.com/ticket-789: Created the reporting dashboard, providing insights into
                            sales and user engagement metrics.

                            OUTPUT CONTRACT:
                            Your response must be in Markdown format.
                            PROMPT
                            ,
                    ],
                    [
                        'role' => 'user',
                        'content' => $entriesTextFormatted
                    ],
                ],
            ]);

        $data = $response->json();
        $summary = $data['choices'][0]['message']['content'] ?? 'No summary
    available.';
        return Str::markdown($summary);
    }
}
