<?php

namespace App\Providers\Filament;

use Andreia\FilamentUiSwitcher\FilamentUiSwitcherPlugin;
use App\Filament\Taker\Resources\Orders\OrderResource;
use App\Http\Middleware\CheckUserStatus;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Moataz01\FilamentNotificationSound\FilamentNotificationSoundPlugin;
use Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticationPlugin;
use WatheqAlshowaiter\FilamentStickyTableHeader\StickyTableHeaderPlugin;

class PackerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('packer')
            ->path('packer')
            ->viteTheme('resources/css/filament/packer/theme.css')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(Width::Full)
            ->login()
            ->passwordReset()
            ->emailChangeVerification()
            ->emailVerification()
            ->discoverResources(in: app_path('Filament/Packer/Resources'), for: 'App\Filament\Packer\Resources')
            ->discoverPages(in: app_path('Filament/Packer/Pages'), for: 'App\Filament\Packer\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->resources([
                OrderResource::class,
            ])
            ->globalSearch(false)
            ->discoverWidgets(in: app_path('Filament/Paker/Widgets'), for: 'App\Filament\Paker\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
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
                CheckUserStatus::class, // Custom middleware to check user status
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->plugins([
                FilamentUiSwitcherPlugin::make()
                    ->withModeSwitcher(),
                AuthUIEnhancerPlugin::make()
                    ->formPanelPosition('left')
                    ->mobileFormPanelPosition('bottom')
                    ->formPanelBackgroundColor(Color::Slate, '800')
                    ->emptyPanelBackgroundImageOpacity('70%')
                    ->emptyPanelBackgroundImageUrl(asset('images/bg.jpg')),
                TwoFactorAuthenticationPlugin::make()
                    ->enableTwoFactorAuthentication() // Enable Google 2FA
                    ->enablePasskeyAuthentication()
                    ->addTwoFactorMenuItem(), // Add 2FA menu item
                FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->setTitle('My Profile')
                    // ->setNavigationLabel('My Profile')
                    // ->setIcon('heroicon-o-user')
                    ->setSort(10)
                    ->shouldRegisterNavigation(false)
                    ->shouldShowEmailForm()
                    ->shouldShowDeleteAccountForm(false)
                    // ->shouldShowSanctumTokens()
                    ->shouldShowMultiFactorAuthentication()
                    ->shouldShowBrowserSessionsForm()
                    ->shouldShowAvatarForm(
                        value: true,
                        directory: 'avatars', // image will be stored in 'storage/app/public/avatars
                        rules: 'mimes:jpeg,png|max:2048' //only accept jpeg and png files with a maximum size of 2MB
                    ),
                FilamentApexChartsPlugin::make(),
                StickyTableHeaderPlugin::make(),
            ]);
    }
}
