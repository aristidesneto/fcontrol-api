<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Entry;
use Aristides\Helpers\Helpers;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\EntryResource;

class EntryService
{
    public function list(array $data)
    {
        $query = Entry::with('category', 'creditCard');

        if ($data['order_by']) {
            $arrOrderBy = explode(':', $data['order_by']);
            $query->orderBy($arrOrderBy[1], $arrOrderBy[0] === '-' ? 'desc' : 'asc');
        }

        if ($data['start_period'] && $data['end_period']) {
            $query->whereDate($arrOrderBy[1], '>=', $data['start_period'] . '-01')
                ->whereDate($arrOrderBy[1], '<=', $data['end_period'] . '-01');
        }

        if ($data['start_period'] && $data['end_period']) {
            $query->whereDate($arrOrderBy[1], '>=', $data['start_period'] . '-01')
                ->whereDate($arrOrderBy[1], '<=', $data['end_period'] . '-01');
        }

        if ($data['type']) {
            $type = $data['type'] === 'expense' ? 'expense' : 'income';
            $query->where('type', $type);
        }
        
        return $query->get();
    }

    public function store(array $data)
    {
        if ($data['type'] === 'expense') {
            return $this->createExpense($data);
        }

        return $this->createIncome($data);
    }

    protected function createExpense(array $data)
    {
        
        $due_date = $data['due_date'];

        // Recorrente
        if ($data['is_recurring'] === true) {
            $sequence = $this->getSequence();
            $data['sequence'] = $sequence ? $sequence + 1 : 1;
            $data['bank_account_id'] = null;
            $data['credit_card_id'] = null;
            $data['parcel'] = 0;

            // dd($data);

            for ($i = 1; $i <= 120; ++$i) { // 10 anos
                Entry::create($data);
                $data['due_date'] = $due_date->copy()->addMonthNoOverflow($i);
            }

            return [
                "status" => "success",
                "message" => "Despesa cadastrada com sucesso",
            ];
        }

        
        // Cartão de crédito
        if (isset($data['credit_card_id']) && ! is_null($data['credit_card_id'])) {
            $totalParcel = ($data['parcel'] === null || $data['parcel'] === '0') ? '1' : $data['parcel'];
                        
            $amountParcel = round($data['amount'] / $totalParcel, 2);
            $difference = round(($amountParcel * $totalParcel) - $data['amount'], 2);
            
            // dd($data);
            // dd($amountParcel, $data['amount']);

            $data['total_parcel'] = $totalParcel;
            $data['bank_account_id'] = null;

            for ($i = 1; $i <= $totalParcel; ++$i) {
                $data['parcel'] = $i;
                $data['amount'] = $i === (int) $totalParcel ? $amountParcel - $difference : $amountParcel;
                // dd($data);
                Entry::create($data);

                $data['due_date'] = $due_date->copy()->addMonthNoOverflow($i);
            }

            return [
                "status" => "success",
                "message" => "Despesa cadastrada com sucesso",
            ];
        }

        $data['bank_account_id'] = null;
        $data['credit_card_id'] = null;
        $data['parcel'] = '0';

        //dd($data);

        // Cria para 1 parcela
        $entry = Entry::create($data);

        return [
            "message" => "Despesa cadastrada com sucesso",
            "data" => $entry,
        ];
    }

    protected function getSequence()
    {
        return Entry::max('sequence');
    }

    protected function createIncome(array $data)
    {
        $data['is_recurring'] = $data['is_recurring'] == '1' ? true : false;
        $start_date = $data['start_date'];
        $data['parcel'] = 0;

        if ($data['is_recurring'] === true) {
            for ($i = 1; $i <= 120; ++$i) { // 10 anos
                Entry::create($data);
                
                $data['start_date'] = $start_date->copy()->addMonthNoOverflow($i);
            }
            
            return [
                "status" => "success",
                "message" => "Receita cadastrada com sucesso",
            ];
        }
        
        // dd($data);
        Entry::create($data);

        return [
            "status" => "success",
            "message" => "Receita cadastrada com sucesso",
        ];
    }

    public function findById(int $id)
    {
        return new EntryResource(Entry::find($id));
    }

    public function update(array $data, int $id): array
    {
        $entry = Entry::find($id)->update($data);

        return [
            "message" => "Registro atualizado com sucesso",
            "data" => $entry,
        ];
    }

    public function delete(int $id)
    {
        $entry = Entry::find($id)->delete();

        if ($entry) {
            return [
                "status" => "success",
                "message" => "Receita removida com sucesso"
            ];
        }
    }
}
