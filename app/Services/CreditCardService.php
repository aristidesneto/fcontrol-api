<?php

namespace App\Services;

use App\Http\Resources\CreditCardResource;
use App\Models\CreditCard;

class CreditCardService
{
    public function list()
    {
        return CreditCard::orderBy('name')->get();
    }

    public function calculate_next_due_date(?string $date, string $id)
    {
        $creditCard = $this->findById($id);

        $next_due_date = calculate_due_date($creditCard->due_date, $creditCard->best_date, $date);

        return [
            'next_due_date' => $next_due_date
        ];
    }

    public function findById(string $id)
    {
        $creditCard = CreditCard::find((int) $id);

        if (! $creditCard) {
            abort(404, 'Cartão de crédito não encontrado');
        }

        return $creditCard;
    }
}
