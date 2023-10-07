<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Entry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class EntryService
{
    public function list(array $data): LengthAwarePaginator
    {
        // Defaults
        $type = 'income';
        $fieldSearchDefault = 'start_date';
        $paginate = $data['total_page'] ?? 10;

        $data['start_period'] = Carbon::createFromFormat('Y-m', $data['start_period'])->firstOfMonth()->format('Y-m-d');
        $data['end_period'] = Carbon::createFromFormat('Y-m', $data['end_period'])->lastOfMonth()->format('Y-m-d');

        if (isset($data['type']) && $data['type'] === 'expense') {
            $type = 'expense';
            $fieldSearchDefault = 'due_date';
        }        

        $query = Entry::with('category', 'creditCard')
            ->entryType($type);

        // Se cartão
        if (isset($data['credit_card_id'])) {
            $creditCardId = $data['credit_card_id'];
            $query->whereHas('creditCard', function (Builder $query) use ($creditCardId) {
                $query->where('id', $creditCardId);
            });
        }

        if ($data['order_by']) {
            $arrOrderBy = explode(':', $data['order_by']);
            $query->orderBy($arrOrderBy[1], $arrOrderBy[0] === '-' ? 'asc' : 'desc');
        }

        if ($data['start_period'] && $data['end_period']) {
            $query->whereDate($fieldSearchDefault, '>=', $data['start_period'])
                ->whereDate($fieldSearchDefault, '<=', $data['end_period']);
        }
        
        return $query->paginate($paginate);
    }

    public function store(array $data)
    {
        if ($data['type'] === 'expense') {
            return $this->createExpense($data);
        }

        return $this->createIncome($data);
    }

    protected function createExpense(array $data): Entry
    {
        if ($data['is_recurring'] === true) {
            return $this->saveRecurrent($data);
        }
        
        if (isset($data['credit_card_id']) && ! is_null($data['credit_card_id'])) {
            return $this->saveCreditCard($data);
        }

        return $this->saveGeneral($data);        
    }

    protected function saveRecurrent(array $data)
    {
        $data['sequence'] = $this->getSequence();
        $data['bank_account_id'] = null;
        $data['credit_card_id'] = null;
        $data['parcel'] = 0;
        $due_date = $data['due_date'];

        $newArr = collect();
        for ($i = 1; $i <= 60; ++$i) {        
            $newArr->push($data);
            $data['due_date'] = $due_date->copy()->addMonthNoOverflow($i);
        }
        $user = auth()->user();
        
        $user->entries()->createMany($newArr->toArray());

        return Entry::where('sequence', $data['sequence'])
            ->orderBy('id', 'ASC')
            ->first();
    }

    protected function saveCreditCard(array $data): Entry
    {
        $totalParcel = ($data['parcel'] === null || $data['parcel'] === '0') ? '1' : $data['parcel'];
        $due_date = $data['due_date'];      
        $amountParcel = round($data['amount'] / $totalParcel, 2);
        $difference = round(($amountParcel * $totalParcel) - $data['amount'], 2);

        $data['total_parcel'] = $totalParcel;
        $data['bank_account_id'] = null;

        for ($i = 1; $i <= $totalParcel; ++$i) {
            $data['parcel'] = $i;
            $data['amount'] = $i === (int) $totalParcel ? $amountParcel - $difference : $amountParcel;

            $entry = Entry::create($data);
            $data['due_date'] = $due_date->copy()->addMonthNoOverflow($i);
        }

        return $entry;
    }

    protected function saveGeneral(array $data): Entry
    {
        $data['sequence'] = $this->getSequence();
        $data['bank_account_id'] = null;
        $data['credit_card_id'] = null;
        $totalParcel = ($data['parcel'] === null || $data['parcel'] === '0') ? '1' : $data['parcel'];
        $due_date = $data['due_date'];
        $data['total_parcel'] = $totalParcel;
        $amountParcel = round($data['amount'] / $totalParcel, 2);
        $difference = round(($amountParcel * $totalParcel) - $data['amount'], 2);

        if ($data['parcel'] > 1) {
            $newArr = collect();
            for ($i = 1; $i <= $totalParcel; ++$i) {
                $data['parcel'] = $i;
                $data['amount'] = $i === (int) $data['parcel'] ? $amountParcel - $difference : $amountParcel;
                $newArr->push($data);
                $data['due_date'] = $due_date->copy()->addMonthNoOverflow($i);
            }
            $user = auth()->user();
        
            $user->entries()->createMany($newArr->toArray());
            
            return Entry::where('sequence', $data['sequence'])
                ->orderBy('id', 'ASC')
                ->first();
        }

        return Entry::create($data);
    }

    protected function getSequence(): Int
    {
        $sequence = Entry::max('sequence');

        return $sequence ? $sequence + 1 : 1;
    }

    protected function createIncome(array $data): Entry
    {
        $data['is_recurring'] = $data['is_recurring'] == '1' ? true : false;
        $start_date = $data['start_date'];
        $data['parcel'] = 0;
        $data['credit_card_id'] = null;

        if ($data['is_recurring'] === true) {
            $data['sequence'] = $this->getSequence();            
            for ($i = 1; $i <= 60; ++$i) { // 5 anos
                $entry = Entry::create($data);
                
                $data['start_date'] = $start_date->copy()->addMonthNoOverflow($i);
            }
            
            return Entry::where('sequence', $entry->sequence)
                ->orderBy('id', 'ASC')
                ->first();
        }

        return Entry::create($data);
    }

    public function show(string $id): Entry
    {
        return $this->find($id);
    }

    public function find(string $id): Entry
    {
        $entry = Entry::with('creditCard', 'category')->find((int) $id);

        if (! $entry) {
            abort(404);
        }

        return $entry;
    }

    public function update(array $data, string $id): Entry
    {
        $entry = $this->find($id);
        
        $entry->update($data);

        return $entry;
    }

    public function payday(array $data, string $id): Entry
    {
        $entry = $this->find($id);

        $entry->update($data);

        return $entry;
    }

    public function delete(string $id): bool
    {
        $entry = $this->find($id);

        return $entry->delete();
    }
}
