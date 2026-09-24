<?php

namespace App\Filament\Resources\Interests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class InterestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sender_id')
                    ->relationship('sender', 'name')
                    ->required(),
                Select::make('receiver_id')
                    ->relationship('receiver', 'name')
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
            ]);
    }
}
