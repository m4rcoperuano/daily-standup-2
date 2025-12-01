<?php

namespace App\Filament\Resources\SocialiteIntegrations;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SocialiteIntegrations\Pages\ListSocialiteIntegrations;
use App\Filament\Resources\SocialiteIntegrations\Pages\CreateSocialiteIntegration;
use App\Filament\Resources\SocialiteIntegrations\Pages\EditSocialiteIntegration;
use App\Filament\Resources\SocialiteIntegrationResource\Pages;
use App\Filament\Resources\SocialiteIntegrationResource\RelationManagers;
use App\Models\SocialiteIntegration;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SocialiteIntegrationResource extends Resource
{
    protected static bool $shouldSkipAuthorization = true;
    protected static ?string $model = SocialiteIntegration::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('provider')
                    ->required()
                    ->maxLength(255),
                TextInput::make('provider_user_name'),
                TextInput::make('provider_user_avatar'),
                TextInput::make('provider_user_email'),
                TextInput::make('provider_user_nick_name'),
                TextInput::make('provider_user_id'),
                TextInput::make('version')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('provider_user_avatar')
                    ->label('Avatar'),
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('provider')
                    ->searchable(),
                TextColumn::make('provider_user_name')
                    ->label('Name'),
                TextColumn::make('provider_user_email')
                    ->label('Email'),
                TextColumn::make('provider_user_nick_name')
                    ->label('Nickname'),
                TextColumn::make('provider_user_id')
                    ->label('Provider ID'),
                TextColumn::make('version')
                    ->label('Version')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSocialiteIntegrations::route('/'),
            'create' => CreateSocialiteIntegration::route('/create'),
            'edit' => EditSocialiteIntegration::route('/{record}/edit'),
        ];
    }
}
