<?php

namespace App\Filament\Resources\StandUpEntries\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\StandUpEntries\StandUpEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStandUpEntries extends ListRecords
{
    protected static string $resource = StandUpEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
