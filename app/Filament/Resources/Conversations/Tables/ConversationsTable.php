<?php

namespace App\Filament\Resources\Conversations\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConversationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('userOne.name')
                    ->label('Participant 1')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('userTwo.name')
                    ->label('Participant 2')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('last_message_at')
                    ->label('Last Message Time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('last_message_at', 'desc');
    }
}
