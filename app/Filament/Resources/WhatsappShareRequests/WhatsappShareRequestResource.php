<?php

namespace App\Filament\Resources\WhatsappShareRequests;

use App\Filament\Resources\WhatsappShareRequests\Pages\ListWhatsappShareRequests;
use App\Filament\Resources\WhatsappShareRequests\Tables\WhatsappShareRequestsTable;
use App\Models\WhatsappShareRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WhatsappShareRequestResource extends Resource
{
    protected static ?string $model = WhatsappShareRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static string|\UnitEnum|null $navigationGroup = 'COMMUNICATION';

    protected static ?string $navigationLabel = 'WhatsApp Requests';

    protected static ?string $recordTitleAttribute = 'id';

    public static function table(Table $table): Table
    {
        return WhatsappShareRequestsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWhatsappShareRequests::route('/'),
        ];
    }
}
