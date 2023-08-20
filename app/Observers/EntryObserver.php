<?php

namespace App\Observers;

use App\Models\Entry;
use Illuminate\Support\Str;

class EntryObserver
{
    public function creating(Entry $entry): void
    {
        $entry->uuid = Str::uuid()->toString();
        $entry->user_id = auth()->user()->uuid;
    }
}
