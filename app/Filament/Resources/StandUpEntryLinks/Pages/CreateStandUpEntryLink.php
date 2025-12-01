<?php

namespace App\Filament\Resources\StandUpEntryLinks\Pages;

use App\Filament\Resources\StandUpEntryLinks\StandUpEntryLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStandUpEntryLink extends CreateRecord
{
    protected static string $resource = StandUpEntryLinkResource::class;
}
