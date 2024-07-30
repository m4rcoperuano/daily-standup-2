<?php

namespace App\Filament\Resources\StandUpEntryResource\Pages;

use App\Filament\Resources\StandUpEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStandUpEntry extends EditRecord
{
    protected static string $resource = StandUpEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
