<?php

namespace App\Filament\Resources\MemberProfiles\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MemberProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('first_name')
                    ->maxLength(100),
                TextInput::make('last_name')
                    ->maxLength(100),
                DatePicker::make('date_of_birth'),
                Select::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ]),
                Select::make('marital_status')
                    ->options([
                        'Unmarried' => 'Unmarried (অবিবাহিত)',
                        'Married (Seeking 2nd Marriage)' => 'Married - Seeking 2nd Marriage (বিবাহিত - ২য় বিবাহ)',
                        'Divorced' => 'Divorced (ডিভোর্সড)',
                        'Widowed' => 'Widowed (বিধবা / বিপত্নীক)',
                        'Single Parent' => 'Single Parent (সিঙ্গেল প্যারেন্ট)',
                    ]),
                Select::make('religion')
                    ->options([
                        'Islam' => 'Islam',
                        'Hinduism' => 'Hinduism',
                        'Christianity' => 'Christianity',
                        'Buddhism' => 'Buddhism',
                        'Other' => 'Other',
                    ]),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(30),
                TextInput::make('city')
                    ->maxLength(100),
                TextInput::make('country')
                    ->maxLength(100),
                TextInput::make('location')
                    ->maxLength(255),
                TextInput::make('height')
                    ->numeric()
                    ->label('Height (inches)')
                    ->suffix('in')
                    ->helperText("Total inches (e.g. 64 for 5'4\", 66 for 5'6\", 68 for 5'8\")"),
                TextInput::make('education')
                    ->maxLength(255),
                TextInput::make('occupation')
                    ->maxLength(255),
                TextInput::make('children_count')
                    ->numeric()
                    ->default(0),
                Textarea::make('about_me')
                    ->maxLength(2000)
                    ->columnSpanFull(),
                Textarea::make('partner_expectation')
                    ->label('Partner Preference & Interest (কেমন পাত্র / পাত্রী খুঁজছেন)')
                    ->maxLength(2000)
                    ->columnSpanFull(),
                FileUpload::make('profile_photo_path')
                    ->disk('public')
                    ->directory('profile-photos')
                    ->image()
                    ->maxSize(10240)
                    ->label('Profile Photo'),
                Toggle::make('is_profile_visible')
                    ->label('Visible on Platform')
                    ->default(true),
                Toggle::make('is_profile_complete')
                    ->label('Profile 100% Completed'),
            ]);
    }
}
