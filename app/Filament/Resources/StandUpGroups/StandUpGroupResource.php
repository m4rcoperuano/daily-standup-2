<?php

namespace App\Filament\Resources\StandUpGroups;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\StandUpGroups\Pages\ListStandUpGroups;
use App\Filament\Resources\StandUpGroups\Pages\CreateStandUpGroup;
use App\Filament\Resources\StandUpGroups\Pages\EditStandUpGroup;
use App\Filament\Resources\StandUpGroupResource\Pages;
use App\Filament\Resources\StandUpGroupResource\RelationManagers;
use App\Models\StandUpGroup;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StandUpGroupResource extends Resource
{
    protected static bool $shouldSkipAuthorization = true;

    protected static ?string $model = StandUpGroup::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('team_id')
                    ->relationship('team', 'name')
                    ->required(),
                TextInput::make('atlassian_board_id')
                    ->required()
                    ->maxLength(255),
                TextInput::make('atlassian_sprint_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('team.name')
                    ->numeric()
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
            'index' => ListStandUpGroups::route('/'),
            'create' => CreateStandUpGroup::route('/create'),
            'edit' => EditStandUpGroup::route('/{record}/edit'),
        ];
    }
}
