<?php

namespace App\Providers;

use App\Database\Query\Grammars\MySqlGrammar;
use App\Models\Entry;
use App\Models\Category;
use App\Models\CreditCard;
use App\Observers\EntryObserver;
use App\Observers\CategoryObserver;
use App\Observers\CreditCardObserver;
use Illuminate\Support\Facades\DB;
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
            Category::observe(CategoryObserver::class);
            Entry::observe(EntryObserver::class);
            CreditCard::observe(CreditCardObserver::class);
        }

        // Schema::defaultStringLength(191);
        
        DB::connection()->setQueryGrammar(new MySqlGrammar);
    }
}
