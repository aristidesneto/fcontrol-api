<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Entry;
use App\Models\Category;
use App\Models\CreditCard;
use App\Observers\EntryObserver;
use Illuminate\Support\Facades\DB;
use App\Observers\CategoryObserver;
use Illuminate\Support\Facades\Auth;
use App\Observers\CreditCardObserver;
use Illuminate\Support\ServiceProvider;
use App\Database\Query\Grammars\MySqlGrammar;

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

        // if (Auth::check()) {
        //     $timezone = auth()->user()->timezone;

        //     // Defina o fuso horário padrão sempre que Carbon for instanciado
        //     Carbon::macro('setDefaultTimeZone', function () {
        //         return static::setToStringFormat($timezone)
        //         ->setTimezone($timezone);
        //     });
        // }

        // Schema::defaultStringLength(191);

        DB::connection()->setQueryGrammar(new MySqlGrammar);
    }
}
