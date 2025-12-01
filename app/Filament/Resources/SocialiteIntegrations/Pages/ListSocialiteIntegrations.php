<?php

namespace App\Filament\Resources\SocialiteIntegrations\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SocialiteIntegrations\SocialiteIntegrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocialiteIntegrations extends ListRecords
{
    protected static string $resource = SocialiteIntegrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
