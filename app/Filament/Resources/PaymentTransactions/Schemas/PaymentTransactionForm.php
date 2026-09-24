<?php

namespace App\Filament\Resources\PaymentTransactions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('membership_plan_id')
                    ->relationship('membershipPlan', 'name')
                    ->required(),
                TextInput::make('transaction_id')
                    ->required(),
                TextInput::make('bank_transaction_id')
                    ->nullable(),
                TextInput::make('amount')
                    ->numeric()
                    ->required(),
                Select::make('currency')
                    ->options([
                        'BDT' => 'BDT (৳)',
                        'USD' => 'USD ($)',
                    ])
                    ->required(),
                Select::make('status')
                    ->options([
                        'initiated' => 'Initiated',
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ])
                    ->required(),
                TextInput::make('gateway')
                    ->default('sslcommerz')
                    ->required(),
                DateTimePicker::make('paid_at'),
                DateTimePicker::make('failed_at'),
            ]);
    }
}
