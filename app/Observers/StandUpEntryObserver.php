<?php

namespace App\Observers;

use App\Models\StandUpEntry;

class StandUpEntryObserver
{
    public function created(StandUpEntry $entry)
    {
        //dd($entry);
    }
}
