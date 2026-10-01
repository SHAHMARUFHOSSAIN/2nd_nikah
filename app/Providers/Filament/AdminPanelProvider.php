<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName(fn () => \App\Models\Setting::get('site_name', '2nd Nikah') . ' Admin')
            ->brandLogo(fn () => \App\Models\Setting::getUrl('logo_path'))
            ->brandLogoHeight('2.5rem')
            ->favicon(fn () => \App\Models\Setting::getUrl('favicon_path'))
            ->colors([
                'primary' => Color::Rose,
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Sky,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'purple' => Color::Purple,
                'indigo' => Color::Indigo,
                'teal' => Color::Teal,
                'pink' => Color::Pink,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\StatsOverviewWidget::class,
                \App\Filament\Widgets\RecentMembersWidget::class,
            ])
            ->font('Outfit')
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                'MEMBERS',
                'CONNECTIONS',
                'COMMUNICATION',
                'MEMBERSHIP & PAYMENTS',
                'CONTENT',
                'SYSTEM',
            ])
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): string => \Illuminate\Support\Facades\Blade::render('
                    <style>
                        /* Glassmorphism Filament Admin Custom Styling */
                        .fi-main {
                            background: radial-gradient(circle at 10% 20%, rgba(255, 241, 242, 0.8) 0%, rgba(253, 242, 248, 0.4) 40%, rgba(248, 250, 252, 1) 90%) !important;
                        }

                        /* Glassmorphism Topbar */
                        .fi-topbar {
                            background: rgba(255, 255, 255, 0.75) !important;
                            backdrop-filter: blur(16px) saturate(180%) !important;
                            -webkit-backdrop-filter: blur(16px) saturate(180%) !important;
                            border-bottom: 1px solid rgba(255, 255, 255, 0.6) !important;
                            box-shadow: 0 4px 30px rgba(225, 29, 72, 0.04) !important;
                        }

                        /* Glassmorphism Sidebar */
                        .fi-sidebar {
                            background: rgba(255, 255, 255, 0.8) !important;
                            backdrop-filter: blur(20px) saturate(180%) !important;
                            -webkit-backdrop-filter: blur(20px) saturate(180%) !important;
                            border-right: 1px solid rgba(255, 255, 255, 0.7) !important;
                            box-shadow: 0 8px 32px 0 rgba(225, 29, 72, 0.05) !important;
                        }

                        .fi-sidebar-header {
                            border-bottom: 1px solid rgba(225, 29, 72, 0.08) !important;
                        }

                        .fi-sidebar-group-label {
                            color: #9F1239 !important;
                            font-weight: 800 !important;
                            letter-spacing: 0.08em !important;
                            font-size: 0.68rem !important;
                            text-transform: uppercase !important;
                        }

                        .fi-sidebar-item-button {
                            color: #334155 !important;
                            border-radius: 0.75rem !important;
                            font-weight: 600 !important;
                            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                        }

                        .fi-sidebar-item-button:hover {
                            background: rgba(225, 29, 72, 0.08) !important;
                            color: #E11D48 !important;
                            transform: translateX(4px) !important;
                        }

                        .fi-sidebar-item-active .fi-sidebar-item-button {
                            background: linear-gradient(135deg, #E11D48 0%, #EC4899 100%) !important;
                            color: #FFFFFF !important;
                            font-weight: 800 !important;
                            box-shadow: 0 6px 20px rgba(225, 29, 72, 0.35) !important;
                        }

                        .fi-sidebar-item-icon {
                            color: #E11D48 !important;
                        }

                        .fi-sidebar-item-active .fi-sidebar-item-icon {
                            color: #FFFFFF !important;
                        }

                        /* Page Headers & Titles */
                        .fi-header-heading {
                            font-family: "Outfit", sans-serif !important;
                            font-weight: 800 !important;
                            letter-spacing: -0.02em !important;
                            color: #0F172A !important;
                        }

                        /* Glassmorphism Dashboard Stat Cards */
                        .fi-wi-stats-overview-stat {
                            border-radius: 1.25rem !important;
                            border: 1px solid rgba(255, 255, 255, 0.8) !important;
                            background: rgba(255, 255, 255, 0.7) !important;
                            backdrop-filter: blur(16px) saturate(180%) !important;
                            -webkit-backdrop-filter: blur(16px) saturate(180%) !important;
                            box-shadow: 0 8px 32px 0 rgba(225, 29, 72, 0.06) !important;
                            transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease !important;
                            overflow: hidden !important;
                        }

                        .fi-wi-stats-overview-stat:hover {
                            transform: translateY(-4px) !important;
                            background: rgba(255, 255, 255, 0.9) !important;
                            box-shadow: 0 14px 40px 0 rgba(225, 29, 72, 0.15) !important;
                            border-color: rgba(244, 114, 182, 0.5) !important;
                        }

                        /* Glassmorphism Table Cards & Containers */
                        .fi-ta-header-container, .fi-ta-content, .fi-ta-table {
                            border-radius: 1.25rem !important;
                        }

                        .fi-ta-header-cell {
                            background-color: rgba(255, 241, 242, 0.85) !important;
                            backdrop-filter: blur(8px) !important;
                            color: #881337 !important;
                            font-weight: 800 !important;
                            text-transform: uppercase !important;
                            font-size: 0.72rem !important;
                            letter-spacing: 0.05em !important;
                        }

                        .fi-ta-row {
                            transition: background-color 0.15s ease !important;
                        }

                        .fi-ta-row:hover {
                            background-color: rgba(255, 241, 242, 0.6) !important;
                        }

                        .fi-section {
                            border-radius: 1.25rem !important;
                            border: 1px solid rgba(255, 255, 255, 0.8) !important;
                            background: rgba(255, 255, 255, 0.75) !important;
                            backdrop-filter: blur(16px) !important;
                            -webkit-backdrop-filter: blur(16px) !important;
                            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.04) !important;
                        }

                        .fi-badge {
                            border-radius: 9999px !important;
                            font-weight: 700 !important;
                            letter-spacing: 0.02em !important;
                            padding-left: 0.75rem !important;
                            padding-right: 0.75rem !important;
                        }

                        /* Glassmorphism Primary Buttons in Admin */
                        .fi-btn-primary {
                            background: linear-gradient(135deg, #E11D48 0%, #EC4899 100%) !important;
                            border-radius: 0.75rem !important;
                            font-weight: 700 !important;
                            box-shadow: 0 4px 14px rgba(225, 29, 72, 0.3) !important;
                            backdrop-filter: blur(8px) !important;
                        }

                        .fi-btn-primary:hover {
                            box-shadow: 0 6px 20px rgba(225, 29, 72, 0.45) !important;
                        }

                        /* Custom Smooth Scrollbar */
                        ::-webkit-scrollbar {
                            width: 6px;
                            height: 6px;
                        }
                        ::-webkit-scrollbar-track {
                            background: transparent;
                        }
                        ::-webkit-scrollbar-thumb {
                            background: rgba(225, 29, 72, 0.25);
                            border-radius: 9999px;
                        }
                        ::-webkit-scrollbar-thumb:hover {
                            background: rgba(225, 29, 72, 0.5);
                        }
                    </style>
                ')
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
