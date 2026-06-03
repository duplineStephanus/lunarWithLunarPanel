<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lunar\Admin\Support\Facades\LunarPanel;
use Filament\Support\Colors\Color;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        LunarPanel::panel(fn ($panel) => $panel
            ->path('admin')
            ->brandName('Lana')
            ->brandLogo(asset('/images/logo-emblem.png'))
            ->brandLogoHeight('4rem')
           ->colors([
                'primary' => Color::hex('#244A32'),
            ]))
        ->register();

        //LunarPanel::register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
