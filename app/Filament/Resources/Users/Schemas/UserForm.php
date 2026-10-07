<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Account Credentials')
                    ->description('Essential account information for login and communication')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->helperText('Leave blank to keep existing password'),
                        DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->default(now()),
                    ])->columns(2),

                Section::make('Role-Based Access Control & Status')
                    ->description('Assign user roles, admin permissions and account status')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Select::make('roles')
                            ->label('Assigned Role(s)')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->helperText('Select one or more roles (e.g. Super Admin, Admin, Moderator, Support Manager, Member). Admin roles grant administrative dashboard access.')
                            ->columnSpanFull(),
                        Toggle::make('is_admin')
                            ->label('Admin Panel Access Flag')
                            ->helperText('Allows access to Filament admin dashboard')
                            ->default(false),
                        Toggle::make('is_active')
                            ->label('Account Active')
                            ->helperText('Inactive users cannot log into the site or admin panel')
                            ->default(true)
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
