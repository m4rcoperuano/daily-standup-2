<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StandUpEntryLinkResource\Pages;
use App\Filament\Resources\StandUpEntryLinkResource\RelationManagers;
use App\Models\StandUpEntryLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StandUpEntryLinkResource extends Resource
{
    protected static bool $shouldSkipAuthorization = true;
    protected static ?string $model = StandUpEntryLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('stand_up_entry_id')
                    ->relationship('standUpEntry', 'id')
                    ->required(),
                Forms\Components\Textarea::make('url')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('host')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('text')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\KeyValue::make('attributes')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('standUpEntry.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('host')
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
            'index' => Pages\ListStandUpEntryLinks::route('/'),
            'create' => Pages\CreateStandUpEntryLink::route('/create'),
            'edit' => Pages\EditStandUpEntryLink::route('/{record}/edit'),
        ];
    }
}
