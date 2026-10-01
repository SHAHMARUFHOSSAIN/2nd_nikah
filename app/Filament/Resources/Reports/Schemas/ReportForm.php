<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('reporter_id')
                    ->relationship('reporter', 'name')
                    ->required()
                    ->searchable(),
                Select::make('reported_user_id')
                    ->relationship('reportedUser', 'name')
                    ->required()
                    ->searchable(),
                TextInput::make('reason')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending Review',
                        'reviewing' => 'Under Review',
                        'resolved' => 'Resolved / Action Taken',
                        'dismissed' => 'Dismissed',
                    ])
                    ->required()
                    ->default('pending'),
                Textarea::make('admin_notes')
                    ->columnSpanFull(),
            ]);
    }
}
