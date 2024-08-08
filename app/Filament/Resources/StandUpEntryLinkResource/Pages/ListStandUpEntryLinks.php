<?php

namespace App\Filament\Resources\StandUpEntryLinkResource\Pages;

use App\Filament\Resources\StandUpEntryLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStandUpEntryLinks extends ListRecords
{
    protected static string $resource = StandUpEntryLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
