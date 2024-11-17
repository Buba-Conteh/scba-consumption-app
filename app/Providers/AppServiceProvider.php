<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

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
     * Bootstrap any application services. the right data for the transfer 
     * add a check for band depositr delivery method, and insert 
     */
    public function boot()
    {


        FilamentView::registerRenderHook(
            'panels::auth.login.form.after',
            fn (): View => view('filament.customeLogin')
        );
 
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales([
                    'en' ,
                    'fr',
                    'ar',
                ]); // also accepts a closure
        });
 
    
    }
}
