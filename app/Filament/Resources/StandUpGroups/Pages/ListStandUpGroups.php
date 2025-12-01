<?php

namespace App\Filament\Resources\StandUpGroups\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\StandUpGroups\StandUpGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStandUpGroups extends ListRecords
{
    protected static string $resource = StandUpGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
