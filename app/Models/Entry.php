<?php

namespace App\Models;

use Aristides\Helpers\Helpers;
use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    use HasFactory, TenantTrait, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'credit_card_id',
        'bank_account_id',
        'type',
        'title',
        'amount',
        'parcel',
        'total_parcel',
        'due_date',
        'payday',
        'is_recurring',
        'start_date',
        'sequence',
        'observation'
    ];

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

    public function setAmountAttribute(string $value): void
    {
        $this->attributes['amount'] = Helpers::formatMoneyToDatabase($value);
    }
}
