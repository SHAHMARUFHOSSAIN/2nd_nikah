<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        /** @var \App\Models\User $user */
        $user = $this->record;
        if ($user->hasAnyRole(['Super Admin', 'Admin', 'Moderator', 'Support Manager'])) {
            $user->updateQuietly(['is_admin' => true]);
        }
    }
}
