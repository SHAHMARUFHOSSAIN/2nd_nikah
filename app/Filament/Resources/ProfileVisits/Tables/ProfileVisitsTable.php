<?php

namespace App\Filament\Resources\ProfileVisits\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProfileVisitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('visitor.name')
                    ->label('Visitor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('visitedUser.name')
                    ->label('Visited Member Profile')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Visited At')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}
