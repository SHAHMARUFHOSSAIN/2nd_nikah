<?php

namespace App\Filament\Resources\Shortlists\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShortlistsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Member')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('shortlistedUser.name')
                    ->label('Shortlisted Profile')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}
