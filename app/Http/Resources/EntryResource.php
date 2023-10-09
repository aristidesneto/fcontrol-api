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
        if ($this->type === 'income') {
            return [
                'id' => $this->id,
                'type' => $this->type,
                'amount' => $this->amount,
                'start_date' => is_null($this->start_date) ? null : $this->start_date->format('Y-m-d'),
                'observation' => $this->observation,
                'created_at' => $this->created_at,
    
                // Relationship
                'category' => new CategoryResource($this->whenLoaded('category')),
                
                // Custom
                'month_extension' => is_null($this->due_date) ? null : month_extension($this->due_date),
            ];
        }

        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'amount' => $this->amount,
            'parcel' => $this->parcel,
            'total_parcel' => $this->total_parcel,
            'due_date' => is_null($this->due_date) ? null : $this->due_date->format('Y-m-d'),
            'payday' => is_null($this->payday) ? null : $this->payday->format('Y-m-d'),
            'is_recurring' => $this->is_recurring,
            'parent_id' => $this->parent_id,
            'start_date' => is_null($this->start_date) ? null : $this->start_date->format('Y-m-d'),
            'observation' => $this->observation,
            'created_at' => $this->created_at,

            // Relationship
            'user' => new UserResource($this->whenLoaded('user')),
            'bank_account' => new BankAccountResource($this->whenLoaded('bankAccount')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'credit_card' => new CreditCardResource($this->whenLoaded('creditCard')),
            
            // Custom
            // 'amount_sum' => $this->amount_sum ? Helpers::formatMoneyToReal($this->amount_sum) : null,
            'month_extension' => is_null($this->due_date) ? null : month_extension($this->due_date),
        ];
    }
}
