<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationCampaignResource\Pages;
use App\Models\NotificationCampaign;
use BackedEnum;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NotificationCampaignResource extends Resource
{
    protected static ?string $model = NotificationCampaign::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|\UnitEnum|null $navigationGroup = 'COMMUNICATION';

    protected static ?string $navigationLabel = 'Notification Broadcasts';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Notification Title')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. Premium Membership Offer'),

                Textarea::make('message')
                    ->label('Notification Message')
                    ->required()
                    ->rows(4)
                    ->placeholder('Write the broadcast message to deliver to targeted members...'),

                Select::make('type')
                    ->label('Notification Type')
                    ->options([
                        'announcement' => '📢 Announcement',
                        'membership' => '💎 Membership',
                        'promotion' => '🎁 Promotion',
                        'system' => '⚙️ System',
                        'security' => '🛡️ Security',
                        'update' => '✨ Update',
                    ])
                    ->required()
                    ->default('announcement'),

                Select::make('audience')
                    ->label('Target Audience Segment')
                    ->options([
                        'all_active' => '👥 All Active Users',
                        'premium' => '👑 Premium / Active Subscribers',
                        'free' => '⚪ Free / Non-Subscribers',
                    ])
                    ->required()
                    ->default('all_active')
                    ->live(),

                TextInput::make('action_label')
                    ->label('Action Button Label (Optional)')
                    ->placeholder('e.g. Upgrade to Premium'),

                TextInput::make('action_url')
                    ->label('Action Target URL (Optional)')
                    ->placeholder('e.g. /membership or /members'),

                Placeholder::make('estimated_recipients')
                    ->label('Server Calculated Audience Size')
                    ->content(function (callable $get) {
                        $audience = $get('audience') ?? 'all_active';
                        $campaign = new NotificationCampaign(['audience' => $audience]);
                        $count = $campaign->calculateRecipientCount();

                        return "{$count} active member(s) currently match this target audience query.";
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Campaign Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'membership' => 'warning',
                        'promotion' => 'purple',
                        'system' => 'info',
                        'security' => 'danger',
                        'update' => 'success',
                        default => 'primary',
                    }),

                TextColumn::make('audience')
                    ->label('Target Audience')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'premium' => '👑 Active Premium',
                        'free' => '⚪ Free Users',
                        default => '👥 All Active Users',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'premium' => 'warning',
                        'free' => 'gray',
                        default => 'info',
                    }),

                TextColumn::make('recipient_count')
                    ->label('Recipients')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sent_count')
                    ->label('Sent')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'sending' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->default('System Admin'),

                TextColumn::make('sent_at')
                    ->label('Sent At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('audience')
                    ->options([
                        'all_active' => 'All Active Users',
                        'premium' => 'Premium Subscribers',
                        'free' => 'Free Users',
                    ]),

                SelectFilter::make('type')
                    ->options([
                        'announcement' => 'Announcement',
                        'membership' => 'Membership',
                        'promotion' => 'Promotion',
                        'system' => 'System',
                        'security' => 'Security',
                        'update' => 'Update',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sending' => 'Sending',
                        'sent' => 'Sent',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                Action::make('sendBroadcast')
                    ->label('Send Broadcast')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn (NotificationCampaign $record): bool => $record->status === 'draft')
                    ->requiresConfirmation()
                    ->modalHeading(fn (NotificationCampaign $record): string => "Send Notification: {$record->title}")
                    ->modalDescription(function (NotificationCampaign $record): string {
                        $count = $record->calculateRecipientCount();
                        $audienceName = match($record->audience) {
                            'premium' => 'Active Premium Subscribers',
                            'free' => 'Free / Non-Subscribers',
                            default => 'All Active Users',
                        };

                        return "Are you sure you want to send this broadcast to {$count} member(s) in the '{$audienceName}' segment? Real database notifications will be persisted immediately.";
                    })
                    ->action(function (NotificationCampaign $record) {
                        $success = $record->sendBroadcast();
                        if ($success) {
                            FilamentNotification::make()
                                ->title('Broadcast Sent Successfully')
                                ->body("Notification delivered to {$record->sent_count} member(s).")
                                ->success()
                                ->send();
                        } else {
                            FilamentNotification::make()
                                ->title('Send Failed')
                                ->body('Broadcast has already been sent or is currently processing.')
                                ->warning()
                                ->send();
                        }
                    }),

                ViewAction::make(),
                EditAction::make()->visible(fn (NotificationCampaign $record): bool => $record->status === 'draft'),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotificationCampaigns::route('/'),
            'create' => Pages\CreateNotificationCampaign::route('/create'),
            'view' => Pages\ViewNotificationCampaign::route('/{record}'),
            'edit' => Pages\EditNotificationCampaign::route('/{record}/edit'),
        ];
    }
}
