<?php

namespace App\Filament\Resources\StandUpGroups\Pages;

use App\Filament\Resources\StandUpGroups\StandUpGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStandUpGroup extends CreateRecord
{
    protected static string $resource = StandUpGroupResource::class;
}
