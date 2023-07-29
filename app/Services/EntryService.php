<?php

namespace App\Services;

use App\Http\Resources\EntryResource;
use Carbon\Carbon;
use App\Models\Entry;
use Aristides\Helpers\Helpers;
use Illuminate\Support\Facades\DB;

class EntryService
{
    public function list(array $data)
    {
        // dd($data);
        $query = Entry::with('category');
        // $query = Entry::with('category')->select("*", DB::raw("sum(amount) as amount_sum"));

        if ($data['order_by']) {
            $arrOrderBy = explode(':', $data['order_by']);
            $query->orderBy($arrOrderBy[1], $arrOrderBy[0] === '-' ? 'desc' : 'asc');
        }

        // dd($arrOrderBy);
        
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
        
        // if ($data['group_by']) {
        //     $query->groupBy('start_date');
        // }
        
        // dd($entry->toArray());
        
        return $query->paginate();
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
        $data['is_recurring'] = $data['is_recurring'] == '1' ? true : false;
        $due_date = $data['due_date'] = Carbon::createFromFormat('d/m/Y', $data['due_date']);
        $data['payday'] = isset($data['payday']) ? Carbon::createFromFormat('d/m/Y', $data['payday']) : null;
        // $data['amount'] = Helpers::formatMoneyToDatabase($data['amount']);

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
                "message" => "Income created successfully",
            ];
        }

        // dd($data);

        // Cartão de crédito
        if (isset($data['credit_card_id']) && ! is_null($data['credit_card_id'])) {
            $totalParcel = ($data['parcel'] === null || $data['parcel'] === '0') ? '1' : $data['parcel'];

            $amountParcel = round($data['amount'] / $totalParcel, 2);
            $difference = round(($amountParcel * $totalParcel) - $data['amount'], 2);

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
                "message" => "Income created successfully",
            ];
        }

        $data['bank_account_id'] = null;
        $data['credit_card_id'] = null;
        $data['parcel'] = '0';

        // Cria para 1 parcela
        Entry::create($data);

        return [
            "status" => "success",
            "message" => "Income created successfully",
        ];
    }

    protected function getSequence()
    {
        return Entry::max('sequence');
    }

    protected function createIncome(array $data)
    {
        $data['is_recurring'] = $data['is_recurring'] == '1' ? true : false;
        // $data['amount'] = Helpers::formatMoneyToDatabase($data['amount']);
        $start_date = $data['start_date'] = Carbon::createFromFormat('m/Y', $data['start_date'])->firstOfMonth();
        $data['parcel'] = 0;
        
        
        if ($data['is_recurring'] === true) {
            for ($i = 1; $i <= 120; ++$i) { // 10 anos
                Entry::create($data);
                
                $data['start_date'] = $start_date->copy()->addMonthNoOverflow($i);
            }
            
            return [
                "status" => "success",
                "message" => "Income created successfully",
            ];
        }
        
        // dd($data);
        Entry::create($data);

        return [
            "status" => "success",
            "message" => "Income created successfully",
        ];
    }

    public function findById(int $id)
    {
        // dd(Entry::find($id));
        return new EntryResource(Entry::find($id));
    }

    public function delete(int $id)
    {
        $entry = Entry::find($id);

        if ($entry->delete()) {
            return [
                "status" => "success",
                "message" => "Income removed successfully"
            ];
        }

        return [
            "status" => "error",
            "message" => "Error to remove entry"
        ];
    }
}
