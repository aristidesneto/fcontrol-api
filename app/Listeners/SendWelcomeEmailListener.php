<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\UserWelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailListener
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
        $user = $event->user;

        Mail::to($user->email)
            ->send(new UserWelcomeMail($user));
    }
}
