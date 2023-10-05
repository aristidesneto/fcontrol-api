<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\Category;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateCategoriesListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $categories = config('agenda.categories');

        foreach ($categories as $category) {
            Category::withoutEvents(function () use ($event, $category) {
                Category::create([
                    'user_id' => $event->user->id,
                    'name' => $category['name'],
                    'color' => $category['color'],
                    'type' => $category['type'],
                    'status' => $category['status'],
                ]);
            });
        }
    }
}
