<?php

namespace App\Filament\Resources\SocialiteIntegrationResource\Pages;

use App\Filament\Resources\SocialiteIntegrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSocialiteIntegration extends EditRecord
{
    protected static string $resource = SocialiteIntegrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
