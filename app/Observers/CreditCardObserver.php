<?php

namespace App\Observers;

use App\Models\CreditCard;
use Illuminate\Support\Str;

class CreditCardObserver
{
    public function creating(CreditCard $creditCard): void
    {
        $creditCard->user_id = auth()->user()->id;
    }
}
