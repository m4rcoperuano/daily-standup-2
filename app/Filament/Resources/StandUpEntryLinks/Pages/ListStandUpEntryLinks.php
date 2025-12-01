<?php

namespace App\Filament\Resources\StandUpEntryLinks\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\StandUpEntryLinks\StandUpEntryLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStandUpEntryLinks extends ListRecords
{
    protected static string $resource = StandUpEntryLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
