<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role Definition')
                    ->description('Set role title and identification parameters')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        TextInput::make('name')
                            ->label('Role Name')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g. Moderator, Admin, Support Manager'),
                        TextInput::make('guard_name')
                            ->label('Guard Name')
                            ->default('web')
                            ->required()
                            ->maxLength(50),
                    ])->columns(2),

                Section::make('Permissions Assignment')
                    ->description('Select system privileges granted to users who have this role')
                    ->icon('heroicon-o-key')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label('Granted Permissions')
                            ->relationship('permissions', 'name')
                            ->columns([
                                'default' => 1,
                                'sm' => 2,
                                'md' => 3,
                            ])
                            ->bulkToggleable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
