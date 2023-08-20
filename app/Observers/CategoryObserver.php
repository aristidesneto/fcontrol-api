<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryObserver
{
    public function creating(Category $category): void
    {
        $category->uuid = Str::uuid()->toString();
        $category->user_id = auth()->user()->uuid;
    }
}
