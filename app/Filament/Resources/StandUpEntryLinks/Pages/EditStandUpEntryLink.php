<?php

namespace App\Filament\Resources\StandUpEntryLinks\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\StandUpEntryLinks\StandUpEntryLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStandUpEntryLink extends EditRecord
{
    protected static string $resource = StandUpEntryLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
