<?php

namespace App\Filament\Resources\StandUpEntries\Pages;

use App\Filament\Resources\StandUpEntries\StandUpEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStandUpEntry extends CreateRecord
{
    protected static string $resource = StandUpEntryResource::class;
}
