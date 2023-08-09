<?php

namespace App\Http\Requests;

use App\Models\BankAccount;
use App\Models\Category;
use App\Models\CreditCard;
use App\Rules\ExistsInModel;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Aristides\Helpers\Helpers;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->segment(3);

        return [
            'type' => [
                Rule::requiredIf($id === null),
                Rule::in(config('agenda.types'))
            ],
            'title' => ['string', 'max:120'],
            'is_recurring' => ['required', 'boolean'],
            'start_date' => ['nullable', 'date:Y-m-d'],
            'category_id' => ['required', new ExistsInModel(Category::class)],
            'credit_card_id' => ['nullable', new ExistsInModel(CreditCard::class)],
            'bank_account_id' => ['nullable', new ExistsInModel(BankAccount::class)],
            'amount' => ['required', 'decimal:2'],
            'due_date' => ['nullable', 'date:Y-m-d'],
            'payday' => ['nullable', 'date:Y-m-d'],
            'parcel' => ['numeric'],
            'observation' => ['nullable', 'string', 'max:160']
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([    
            'start_date' => $this->setStartDate($this->start_date),
            'amount' => Helpers::formatMoneyToDatabase($this->amount),
            'due_date' => $this->due_date ? Carbon::createFromFormat('d/m/Y', $this->due_date) : null,
            'payday' => $this->payday ? Carbon::createFromFormat('d/m/Y', $this->payday) : null,
            'is_recurring' => $this->is_recurring == '1' ? true : false,
        ]);
    }

    protected function setStartDate($value): ?Carbon
    {
        if (is_null($value)) {
            return null;
        }

        if (is_array($value) && Arr::exists($value, 'year') && Arr::exists($value, 'month')) {
            return Carbon::createFromFormat('Y-m', $value['year'] . '-' . $value['month'])->firstOfMonth();
        }
        
        return Carbon::createFromFormat('d/m/Y', $value);        
    }
}
