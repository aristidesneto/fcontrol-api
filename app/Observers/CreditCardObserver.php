<?php

namespace App\Observers;

use App\Models\CreditCard;
use Illuminate\Support\Str;

class CreditCardObserver
{
    public function creating(CreditCard $creditCard): void
    {
        $creditCard->uuid = Str::uuid()->toString();
        $creditCard->user_id = auth()->user()->uuid;
    }
}
