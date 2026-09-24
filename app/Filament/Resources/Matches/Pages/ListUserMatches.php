<?php

namespace App\Filament\Resources\Matches\Pages;

use App\Filament\Resources\Matches\UserMatchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserMatches extends ListRecords
{
    protected static string $resource = UserMatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
