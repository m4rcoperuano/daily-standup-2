<?php

namespace App\Filament\Resources\StandUpGroups\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\StandUpGroups\StandUpGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStandUpGroup extends EditRecord
{
    protected static string $resource = StandUpGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
