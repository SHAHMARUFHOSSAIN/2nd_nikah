<?php

namespace App\Filament\Resources\Messages\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('conversation_id')
                    ->label('Conversation ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('sender.name')
                    ->label('Sender')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->colors([
                        'primary' => 'text',
                        'info' => 'image',
                        'warning' => 'whatsapp_request',
                        'danger' => 'deleted',
                        'secondary' => 'reply',
                    ])
                    ->sortable(),
                TextColumn::make('body')
                    ->label('Message Body')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('read_at')
                    ->label('Read Status')
                    ->dateTime()
                    ->placeholder('Unread')
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime()
                    ->placeholder('Active')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Sent Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
