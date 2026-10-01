<?php

namespace App\Filament\Resources\ProfileVisits;

use App\Filament\Resources\ProfileVisits\Pages\ListProfileVisits;
use App\Filament\Resources\ProfileVisits\Tables\ProfileVisitsTable;
use App\Models\ProfileVisit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProfileVisitResource extends Resource
{
    protected static ?string $model = ProfileVisit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEye;

    protected static string|\UnitEnum|null $navigationGroup = 'MEMBERS';

    protected static ?string $recordTitleAttribute = 'id';

    public static function table(Table $table): Table
    {
        return ProfileVisitsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProfileVisits::route('/'),
        ];
    }
}
