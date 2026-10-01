<?php

namespace App\Filament\Resources\Shortlists;

use App\Filament\Resources\Shortlists\Pages\ListShortlists;
use App\Filament\Resources\Shortlists\Tables\ShortlistsTable;
use App\Models\Shortlist;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShortlistResource extends Resource
{
    protected static ?string $model = Shortlist::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string|\UnitEnum|null $navigationGroup = 'MEMBERS';

    protected static ?string $recordTitleAttribute = 'id';

    public static function table(Table $table): Table
    {
        return ShortlistsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShortlists::route('/'),
        ];
    }
}
