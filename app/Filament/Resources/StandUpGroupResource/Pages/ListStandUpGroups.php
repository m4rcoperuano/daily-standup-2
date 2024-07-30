<?php

namespace App\Filament\Resources\StandUpGroupResource\Pages;

use App\Filament\Resources\StandUpGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStandUpGroups extends ListRecords
{
    protected static string $resource = StandUpGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
