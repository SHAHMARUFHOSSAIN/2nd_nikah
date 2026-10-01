<?php

namespace App\Filament\Resources\Matches;

use App\Filament\Resources\Matches\Pages\CreateUserMatch;
use App\Filament\Resources\Matches\Pages\EditUserMatch;
use App\Filament\Resources\Matches\Pages\ListUserMatches;
use App\Filament\Resources\Matches\Schemas\UserMatchForm;
use App\Filament\Resources\Matches\Tables\UserMatchesTable;
use App\Models\UserMatch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserMatchResource extends Resource
{
    protected static ?string $model = UserMatch::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'CONNECTIONS';

    protected static ?string $navigationLabel = 'Matches & Connections';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return UserMatchForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserMatchesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUserMatches::route('/'),
            'create' => CreateUserMatch::route('/create'),
            'edit' => EditUserMatch::route('/{record}/edit'),
        ];
    }
}
