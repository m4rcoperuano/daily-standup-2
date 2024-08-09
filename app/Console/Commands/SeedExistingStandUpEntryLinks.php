<?php

namespace App\Console\Commands;

use App\Actions\ExtractStandUpEntryLinks;
use App\Models\StandUpEntry;
use Illuminate\Console\Command;

class SeedExistingStandUpEntryLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-existing-stand-up-entry-links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfills stand up entry links for existing stand up entries.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        StandUpEntry::query()
            ->whereDoesntHave('standUpEntryLinks')
            ->each(function (StandUpEntry $standUpEntry) {
                app(ExtractStandUpEntryLinks::class)->execute($standUpEntry);
            });
    }
}
