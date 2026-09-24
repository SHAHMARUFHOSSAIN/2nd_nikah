<?php

namespace App\Filament\Resources\Matches\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserMatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('userOne.name')
                    ->label('User One')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('userTwo.name')
                    ->label('User Two')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('matched_at')
                    ->label('Matched At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
