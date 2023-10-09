<?php

namespace App\Models;

use Aristides\Helpers\Helpers;
use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    use HasFactory, TenantTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'due_date' => 'date',
        'payday' => 'date',
        'start_date' => 'date',
    ];

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creditCard(): BelongsTo
    {
        return $this->belongsTo(CreditCard::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeEntryType(Builder $query, string $type)
    {
        return $query->where('type', $type);
    }

    // public function setAmountAttribute(string $value): void
    // {
    //     $this->attributes['amount'] = Helpers::formatMoneyToDatabase($value);
    // }
}
