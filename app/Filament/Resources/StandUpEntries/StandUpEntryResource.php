<?php

namespace App\Filament\Resources\StandUpEntries;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\StandUpEntries\Pages\ListStandUpEntries;
use App\Filament\Resources\StandUpEntries\Pages\CreateStandUpEntry;
use App\Filament\Resources\StandUpEntries\Pages\EditStandUpEntry;
use App\Filament\Resources\StandUpEntryResource\Pages;
use App\Filament\Resources\StandUpEntryResource\RelationManagers;
use App\Models\StandUpEntry;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StandUpEntryResource extends Resource
{
    protected static bool $shouldSkipAuthorization = true;

    protected static ?string $model = StandUpEntry::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('stand_up_group_id')
                    ->relationship('standUpGroup', 'name'),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                DateTimePicker::make('date')
                    ->required(),
                Textarea::make('in_progress')
                    ->columnSpanFull(),
                Textarea::make('priorities')
                    ->columnSpanFull(),
                Textarea::make('blockers')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('standUpGroup.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date')
                    ->dateTime()
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
            'index' => ListStandUpEntries::route('/'),
            'create' => CreateStandUpEntry::route('/create'),
            'edit' => EditStandUpEntry::route('/{record}/edit'),
        ];
    }
}
