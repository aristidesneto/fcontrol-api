<?php

namespace App\Models;

use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditCard extends Model
{
    use HasFactory, TenantTrait;

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'number',
        'best_date',
        'due_date',
        'limit',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function setLimitAttribute(string $value): void
    // {
    //     $this->attributes['limit'] = Helpers::formatMoneyToDatabase($value);
    // }
}
