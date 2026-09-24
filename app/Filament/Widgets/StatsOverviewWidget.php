<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::where('is_admin', false)->count();
        $verifiedUsers = User::where('is_admin', false)->whereNotNull('email_verified_at')->count();
        $adminUsers = User::where('is_admin', true)->count();

        return [
            Stat::make('Registered Members', $totalUsers)
                ->description('Real members stored in MySQL')
                ->color('primary'),

            Stat::make('Verified Members', $verifiedUsers)
                ->description('Members with verified email')
                ->color('success'),

            Stat::make('Administrators', $adminUsers)
                ->description('Platform admin accounts')
                ->color('warning'),
        ];
    }
}
