<?php

namespace App\Filament\Resources\Matches\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class UserMatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_one_id')
                    ->relationship('userOne', 'name')
                    ->required(),
                Select::make('user_two_id')
                    ->relationship('userTwo', 'name')
                    ->required(),
                DateTimePicker::make('matched_at')
                    ->required(),
            ]);
    }
}
