<?php

namespace App\Filament\Resources\MembershipPlans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MembershipPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Select::make('country_scope')
                    ->options([
                        'BD' => 'Bangladesh (BD)',
                        'INTERNATIONAL' => 'International',
                    ])
                    ->required(),
                TextInput::make('amount')
                    ->numeric()
                    ->prefix('$ / ৳')
                    ->required(),
                Select::make('currency')
                    ->options([
                        'BDT' => 'BDT (৳)',
                        'USD' => 'USD ($)',
                    ])
                    ->required(),
                Select::make('billing_interval')
                    ->options([
                        'weekly' => 'Weekly',
                        'monthly' => 'Monthly',
                    ])
                    ->required(),
                TextInput::make('duration_days')
                    ->numeric()
                    ->required(),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
