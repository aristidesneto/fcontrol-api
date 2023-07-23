<?php

namespace App\Providers;

use App\Models\Entry;
use App\Models\CreditCard;
use App\Observers\EntryObserver;
use App\Observers\CreditCardObserver;
use Illuminate\Support\ServiceProvider;

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
        if (!$this->app->runningInConsole()) {
            Entry::observe(EntryObserver::class);
            CreditCard::observe(CreditCardObserver::class);
        }

        // Schema::defaultStringLength(191);
    }
}
