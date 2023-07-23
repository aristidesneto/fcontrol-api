<?php

namespace App\Tenant\Traits;

use App\Tenant\Scopes\TenantScope;

trait TenantTrait
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new TenantScope());
    }
}
