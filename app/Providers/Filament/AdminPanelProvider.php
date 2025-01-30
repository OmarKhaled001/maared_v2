<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Orion\FilamentGreeter\GreeterPlugin;
use Filament\Http\Middleware\Authenticate;
use Rmsramos\Activitylog\ActivitylogPlugin;
use App\Filament\Widgets\VolunteerMonthlyChart;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Resources\EventResourcesResource\Widgets\StatsOverview;

class AdminPanelProvider extends PanelProvider
{
    
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()
            ->profile(isSimple: false)
            ->font('cairo')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                VolunteerMonthlyChart::class,
                // Widgets\FilamentInfoWidget::class,
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
            //  ->plugin(\BezhanSalleh\FilamentShield\FilamentShieldPlugin::make())
            
            ->plugins([
                GreeterPlugin::make()
                ->action(
                    Action::make('action')
                        ->label('إضافة حدث')
                        ->icon('heroicon-o-squares-plus')
                        ->url('admin/events/create')
                )
                ->message('اهلا يا')
                ->name(text: fn() => auth()->user()->name .'😡')
                ->title('خليك شاطر وسجل مشاركاتك في يومها ومتتعبناش كتير😡 ومتنساش تصلِ علي النبي 🥰 وتدعيي لعمر خالد ياخد اعفا من الجيش🤗')
                ->sort(-99),
                ActivitylogPlugin::make()
                
                ->navigationIcon('heroicon-o-shield-check')->authorize(
                    fn () => auth()->user()->is_admin === 1
                ),FilamentShieldPlugin::make(),
                FilamentApexChartsPlugin::make()

            ])

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
