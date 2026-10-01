<?php

namespace App\Filament\Resources\Blocks;

use App\Filament\Resources\Blocks\Pages\ListBlocks;
use App\Filament\Resources\Blocks\Tables\BlocksTable;
use App\Models\Block;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BlockResource extends Resource
{
    protected static ?string $model = Block::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNoSymbol;

    protected static string|\UnitEnum|null $navigationGroup = 'MEMBERS';

    protected static ?string $recordTitleAttribute = 'id';

    public static function table(Table $table): Table
    {
        return BlocksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlocks::route('/'),
        ];
    }
}
