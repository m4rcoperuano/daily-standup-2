<?php

namespace App\Filament\Resources\SocialiteIntegrations\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SocialiteIntegrations\SocialiteIntegrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSocialiteIntegration extends EditRecord
{
    protected static string $resource = SocialiteIntegrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
