<?php

namespace App\Filament\Resources\Blocks\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('blocker.name')
                    ->label('Blocker')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('blockedUser.name')
                    ->label('Blocked User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}
