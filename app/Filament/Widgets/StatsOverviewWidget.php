<?php

namespace App\Filament\Widgets;

use App\Models\Interest;
use App\Models\Message;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserMatch;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalMembers = User::where('is_admin', false)->count();
        $verifiedMembers = User::where('is_admin', false)->whereNotNull('email_verified_at')->count();
        $activeMembers = User::where('is_admin', false)->where('is_active', true)->count();
        $premiumMembers = Subscription::where('status', 'active')->where('ends_at', '>', now())->count();
        $pendingInterests = Interest::where('status', 'pending')->count();
        $activeMatches = UserMatch::count();
        $unreadMessages = Message::whereNull('read_at')->count();
        $pendingReports = Report::where('status', 'pending')->count();

        return [
            Stat::make('Total Members', $totalMembers)
                ->description('Registered platform users')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart([5, 10, 15, 20, $totalMembers]),

            Stat::make('Verified Members', $verifiedMembers)
                ->description('Email verified accounts')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([2, 6, 12, 18, $verifiedMembers]),

            Stat::make('Active Members', $activeMembers)
                ->description('Public & active members')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('info')
                ->chart([3, 8, 14, $activeMembers]),

            Stat::make('Premium Members', $premiumMembers)
                ->description('Active paid subscriptions')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->chart([1, 2, 4, $premiumMembers]),

            Stat::make('Pending Proposals', $pendingInterests)
                ->description('Interests awaiting response')
                ->descriptionIcon('heroicon-m-heart')
                ->color('purple')
                ->chart([4, 7, 10, $pendingInterests]),

            Stat::make('Active Matches', $activeMatches)
                ->description('Mutual accepted connections')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('teal')
                ->chart([1, 3, 6, $activeMatches]),

            Stat::make('Unread Messages', $unreadMessages)
                ->description('In-app unread chats')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('indigo')
                ->chart([0, 2, 5, $unreadMessages]),

            Stat::make('Pending Reports', $pendingReports)
                ->description('Moderation queue')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->chart([0, 1, $pendingReports]),
        ];
    }
}
