<?php

namespace App\Filament\Resources\StandUpEntries\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\StandUpEntries\StandUpEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStandUpEntry extends EditRecord
{
    protected static string $resource = StandUpEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
