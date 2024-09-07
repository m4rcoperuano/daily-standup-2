<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialiteIntegrationResource\Pages;
use App\Filament\Resources\SocialiteIntegrationResource\RelationManagers;
use App\Models\SocialiteIntegration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SocialiteIntegrationResource extends Resource
{
    protected static bool $shouldSkipAuthorization = true;
    protected static ?string $model = SocialiteIntegration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('provider')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('provider_user_name'),
                Forms\Components\TextInput::make('provider_user_avatar'),
                Forms\Components\TextInput::make('provider_user_email'),
                Forms\Components\TextInput::make('provider_user_nick_name'),
                Forms\Components\TextInput::make('provider_user_id'),
                Forms\Components\TextInput::make('version')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('provider_user_avatar')
                    ->label('Avatar'),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('provider')
                    ->searchable(),
                Tables\Columns\TextColumn::make('provider_user_name')
                    ->label('Name'),
                Tables\Columns\TextColumn::make('provider_user_email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('provider_user_nick_name')
                    ->label('Nickname'),
                Tables\Columns\TextColumn::make('provider_user_id')
                    ->label('Provider ID'),
                Tables\Columns\TextColumn::make('version')
                    ->label('Version')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSocialiteIntegrations::route('/'),
            'create' => Pages\CreateSocialiteIntegration::route('/create'),
            'edit' => Pages\EditSocialiteIntegration::route('/{record}/edit'),
        ];
    }
}
