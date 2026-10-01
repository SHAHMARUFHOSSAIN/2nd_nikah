<?php

namespace App\Filament\Resources\WhatsappShareRequests\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WhatsappShareRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('requester.name')
                    ->label('Requester')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('receiver.name')
                    ->label('Receiver')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('conversation_id')
                    ->label('Conversation ID')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'accepted',
                        'danger' => 'rejected',
                        'secondary' => 'cancelled',
                    ])
                    ->sortable(),
                TextColumn::make('requested_at')
                    ->label('Requested At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('responded_at')
                    ->label('Responded At')
                    ->dateTime()
                    ->placeholder('N/A')
                    ->sortable(),
            ])
            ->defaultSort('requested_at', 'desc');
    }
}
