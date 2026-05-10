<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Repositories\cart\CartRepository;
use App\Repositories\cart\CartRepositoryInterface;
use Illuminate\Support\Facades\View;
use App\Settings\GeneralSettings;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        View::share(
            'generalSettings',
            app(GeneralSettings::class)
        );
    }
}
