<?php

namespace App\Observers;

use App\Models\Entry;

class EntryObserver
{
    public function creating(Entry $entry): void
    {
        $entry->user_id = auth()->user()->id;
    }
}
