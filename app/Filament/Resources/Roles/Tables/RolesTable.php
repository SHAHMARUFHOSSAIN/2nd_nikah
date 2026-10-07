<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Role Name')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Super Admin' => 'danger',
                        'Admin' => 'warning',
                        'Moderator' => 'info',
                        'Support Manager' => 'success',
                        default => 'primary',
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Assigned Users')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('permissions.name')
                    ->badge()
                    ->color('gray')
                    ->label('Permissions')
                    ->limitList(4)
                    ->expandableLimitedList(),
                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Created On')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn ($record) => in_array($record->name, ['Super Admin', 'Admin', 'Member'])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
