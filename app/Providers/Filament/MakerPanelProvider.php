<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Actions\Action;
use Filament\Pages\Dashboard;
use Filament\Support\Enums\Width;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use App\Filament\Taker\Resources\Orders\OrderResource;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Andreia\FilamentUiSwitcher\FilamentUiSwitcherPlugin;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Moataz01\FilamentNotificationSound\FilamentNotificationSoundPlugin;
use WatheqAlshowaiter\FilamentStickyTableHeader\StickyTableHeaderPlugin;
use Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticationPlugin;

class MakerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('maker')
            ->path('maker')
            ->viteTheme('resources/css/filament/maker/theme.css')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(Width::Full)
            ->login()
            ->passwordReset()
            ->emailChangeVerification()
            ->emailVerification()
            ->discoverResources(in: app_path('Filament/Maker/Resources'), for: 'App\Filament\Maker\Resources')
            ->discoverPages(in: app_path('Filament/Maker/Pages'), for: 'App\Filament\Maker\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->resources([
                OrderResource::class,
            ])
            ->globalSearch(false)
            ->discoverWidgets(in: app_path('Filament/Maker/Widgets'), for: 'App\Filament\Maker\Widgets')
            ->widgets([
                AccountWidget::class,
                // FilamentInfoWidget::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->userMenuItems([
                'profile' => Action::make('profile')
                    ->label(fn() => auth()->user()->name)
                    ->url(fn(): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle'),
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
