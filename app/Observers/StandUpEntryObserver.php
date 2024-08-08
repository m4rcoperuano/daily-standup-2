<?php

namespace App\Observers;

use App\Actions\ExtractStandUpEntryLinks;
use App\Models\StandUpEntry;

class StandUpEntryObserver
{
    public function created(StandUpEntry $entry)
    {
        app(ExtractStandUpEntryLinks::class)->execute($entry);
    }

    public function updated(StandUpEntry $entry)
    {
        app(ExtractStandUpEntryLinks::class)->execute($entry);
    }
}
