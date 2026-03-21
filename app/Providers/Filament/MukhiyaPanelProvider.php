<?php

namespace App\Providers\Filament;

use Andreia\FilamentUiSwitcher\FilamentUiSwitcherPlugin;
use App\Http\Middleware\CheckUserStatus;
use App\Livewire\LatestOrders;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditEnv\FilamentEditEnvPlugin;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use MarcelWeidum\Passkeys\PasskeysPlugin;
use Moataz01\FilamentNotificationSound\FilamentNotificationSoundPlugin;
use MWGuerra\FileManager\Filament\Pages\FileManager;
use MWGuerra\FileManager\Filament\Pages\FileSystem;
use MWGuerra\FileManager\FileManagerPlugin;
use Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticationPlugin;
use Tapp\FilamentAuthenticationLog\FilamentAuthenticationLogPlugin;
use WatheqAlshowaiter\FilamentStickyTableHeader\StickyTableHeaderPlugin;

class MukhiyaPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('mukhiya')
            // ->profile()
            ->passwordReset()
            ->path('mukhiya')
            ->viteTheme('resources/css/filament/mukhiya/theme.css')
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(Width::Full)
            ->login()
            ->emailChangeVerification()
            ->emailVerification()
            // ->registration()
            ->colors([
                'primary' => Color::Sky,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigationGroups([
                'Stock Management',
                'Inventory Management',
                'Order Management',
                'User Management',
                'FileManager',
                NavigationGroup::make()
                ->label('Expense Management')
                ->icon(Heroicon::OutlinedSquare3Stack3d)
            ])
            ->globalSearch(false)
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                LatestOrders::class,
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
                CheckUserStatus::class, // Custom middleware to check user status
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
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                FilamentUiSwitcherPlugin::make()
                    ->withModeSwitcher(),
                // FileManagerPlugin::make([
                //     FileSystem::class,
                // ]),
                // PasskeysPlugin::make(),
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
                // FilamentEditEnvPlugin::make()
                //     ->showButton(fn() => auth()->user()->id === 1)
                //     ->setIcon('heroicon-o-cog'),
                FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->setTitle('My Profile')
                    // ->setNavigationLabel('My Profile')
                    // ->setIcon('heroicon-o-user')
                    ->setSort(10)
                    ->shouldRegisterNavigation(false)
                    ->shouldShowEmailForm()
                    ->shouldShowDeleteAccountForm(false)
                    ->shouldShowSanctumTokens()
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
