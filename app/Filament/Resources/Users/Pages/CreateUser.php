<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        /** @var \App\Models\User $user */
        $user = $this->record;
        if ($user->hasAnyRole(['Super Admin', 'Admin', 'Moderator', 'Support Manager'])) {
            $user->updateQuietly(['is_admin' => true]);
        }
    }
}
