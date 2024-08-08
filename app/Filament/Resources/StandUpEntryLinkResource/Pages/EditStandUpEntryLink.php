<?php

namespace App\Filament\Resources\StandUpEntryLinkResource\Pages;

use App\Filament\Resources\StandUpEntryLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStandUpEntryLink extends EditRecord
{
    protected static string $resource = StandUpEntryLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
