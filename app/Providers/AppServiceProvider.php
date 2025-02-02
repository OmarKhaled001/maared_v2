<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
            ->locales(['ar'])
            ->visible(outsidePanels: false)
            ->displayLocale('ar'); // also accepts a closure
        });
        Carbon::setLocale('ar');
        Carbon::setWeekStartsAt(Carbon::SATURDAY); // الأسبوع يبدأ من السبت
        Carbon::setWeekEndsAt(Carbon::FRIDAY); // وينتهي بالجمعة
        Paginator::useBootstrap();

    }
}
