<?php

namespace App\Filament\Resources\StandUpEntryLinks;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\StandUpEntryLinks\Pages\ListStandUpEntryLinks;
use App\Filament\Resources\StandUpEntryLinks\Pages\CreateStandUpEntryLink;
use App\Filament\Resources\StandUpEntryLinks\Pages\EditStandUpEntryLink;
use App\Filament\Resources\StandUpEntryLinkResource\Pages;
use App\Filament\Resources\StandUpEntryLinkResource\RelationManagers;
use App\Models\StandUpEntryLink;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StandUpEntryLinkResource extends Resource
{
    protected static bool $shouldSkipAuthorization = true;
    protected static ?string $model = StandUpEntryLink::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('stand_up_entry_id')
                    ->relationship('standUpEntry', 'id')
                    ->required(),
                Textarea::make('url')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('host')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('text')
                    ->required()
                    ->columnSpanFull(),
                KeyValue::make('attributes')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('standUpEntry.id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('host')
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
            'index' => ListStandUpEntryLinks::route('/'),
            'create' => CreateStandUpEntryLink::route('/create'),
            'edit' => EditStandUpEntryLink::route('/{record}/edit'),
        ];
    }
}
