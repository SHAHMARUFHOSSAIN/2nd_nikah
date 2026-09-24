<?php

namespace App\Filament\Resources\Matches\Pages;

use App\Filament\Resources\Matches\UserMatchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserMatch extends CreateRecord
{
    protected static string $resource = UserMatchResource::class;
}
