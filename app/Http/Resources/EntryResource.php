<?php

namespace App\Http\Resources;

use Aristides\Helpers\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'amount' => $this->amount,
            'parcel' => $this->parcel,
            'total_parcel' => $this->total_parcel,
            'due_date' => $this->due_date,
            'payday' => is_null($this->payday) ? null : $this->payday,
            'is_recurring' => $this->is_recurring,
            'start_date' => is_null($this->start_date) ? null : $this->start_date,
            'sequence' => $this->sequence,
            'observation' => $this->observation,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'bank_account' => new BankAccountResource($this->whenLoaded('bankAccount')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'credit_card' => new CreditCardResource($this->whenLoaded('creditCard')),
            
            // Custom
            // 'amount_sum' => $this->amount_sum ? Helpers::formatMoneyToReal($this->amount_sum) : null,
            'month_extension' => $this->start_date ? month_extension($this->start_date) : null,
        ];
    }
}
