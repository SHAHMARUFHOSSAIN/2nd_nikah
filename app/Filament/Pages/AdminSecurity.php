<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use UnitEnum;

class AdminSecurity extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static string|UnitEnum|null $navigationGroup = 'SYSTEM';

    protected static ?string $navigationLabel = 'Admin Password & Email';

    protected static ?string $title = 'Admin Security & Credentials';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.admin-security';

    public ?array $data = [];

    public function mount(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user) {
            $this->form->fill([
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Admin Profile & Login Email (অ্যাডমিন ইমেইল পরিবর্তন)')
                    ->description('Update your administrative login email address and display name.')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        TextInput::make('name')
                            ->label('Admin Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Admin Login Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Reset Admin Password (পাসওয়ার্ড রিসেট / পরিবর্তন)')
                    ->description('Set a new secure password. Leave blank if you only want to change your email or profile.')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        TextInput::make('new_password')
                            ->label('New Password')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->helperText('Minimum 8 characters. Leave blank if keeping current password.'),
                        TextInput::make('new_password_confirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->revealable()
                            ->same('new_password')
                            ->helperText('Re-type the new password to confirm.'),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            Notification::make()
                ->title('Unauthorized')
                ->danger()
                ->send();
            return;
        }

        $state = $this->form->getState();

        // Validate email uniqueness if changed
        $email = trim($state['email']);
        if ($email !== $user->email) {
            $emailExists = User::where('email', $email)
                ->where('id', '!=', $user->id)
                ->exists();

            if ($emailExists) {
                Notification::make()
                    ->title('Email Already Exists')
                    ->body('The specified email address is already in use by another account.')
                    ->danger()
                    ->send();
                return;
            }
        }

        $user->name = trim($state['name']);
        $user->email = $email;

        // Process password update if provided
        $passwordChanged = false;
        if (! empty($state['new_password'])) {
            if ($state['new_password'] !== ($state['new_password_confirmation'] ?? '')) {
                Notification::make()
                    ->title('Password Confirmation Mismatch')
                    ->body('The new password and confirmation password do not match.')
                    ->danger()
                    ->send();
                return;
            }

            if (strlen($state['new_password']) < 8) {
                Notification::make()
                    ->title('Password Too Short')
                    ->body('The new password must be at least 8 characters in length.')
                    ->danger()
                    ->send();
                return;
            }

            $user->password = Hash::make($state['new_password']);
            $passwordChanged = true;
        }

        $user->save();

        // Reset password fields from form state
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'new_password' => null,
            'new_password_confirmation' => null,
        ]);

        $message = $passwordChanged 
            ? 'Admin email and new password updated successfully!'
            : 'Admin profile and email updated successfully!';

        Notification::make()
            ->title('Credentials Updated')
            ->body($message)
            ->success()
            ->send();
    }
}
