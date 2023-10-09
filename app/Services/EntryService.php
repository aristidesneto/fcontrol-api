<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Entry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class EntryService
{
    private int $daysForRecurrence = 60;

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
        // Cria despesa recorrente
        if ($data['is_recurring'] === true) {
            $data['bank_account_id'] = null;
            $data['credit_card_id'] = null;
            $data['parcel'] = 1;
            $data['total_parcel'] = 1;
            $due_date = $data['due_date'];

            $newArr = collect();

            for ($i = 1; $i <= $this->daysForRecurrence; ++$i) {        
                $newArr->push($data);
                $data['due_date'] = $due_date->copy()->addMonthNoOverflow($i);
            }
            
            $user = auth()->user();
            
            $entry = $user->entries()->create($newArr->first());
            $lastId = $entry->id;
            $entry->parent_id = $lastId;
            $entry->save();

            $newData = $newArr->map(function ($item) use ($lastId) {
                $item['parent_id'] = $lastId;
                return $item;
            });

            $newData->forget(0);
            
            $user->entries()->createMany($newData->toArray());

            return $entry;
        }
        
        if (isset($data['credit_card_id']) && ! is_null($data['credit_card_id'])) {
            return $this->saveCreditCard($data);
        }

        return $this->saveGeneral($data);        
    }

    protected function saveCreditCard(array $data): Entry
    {
        $data['total_parcel'] = $data['parcel'];
        $data['bank_account_id'] = null;
        $due_date = $data['due_date'];      
        
        $amountParcel = round($data['amount'] / $data['total_parcel'], 2);
        $difference = round(($amountParcel * $data['total_parcel']) - $data['amount'], 2);

        $newArr = collect();

        for ($i = 1; $i <= $data['total_parcel']; ++$i) {
            $data['parcel'] = $i;
            $data['amount'] = $i === (int) $data['total_parcel'] ? $amountParcel - $difference : $amountParcel;
            $newArr->push($data);    
            $data['due_date'] = $due_date->copy()->addMonthNoOverflow($i);
        }

        $user = auth()->user();

        $entry = $user->entries()->create($newArr->first());
        $lastId = $entry->id;
        $entry->parent_id = $lastId;
        $entry->save();

        $newData = $newArr->map(function ($item) use ($lastId) {
            $item['parent_id'] = $lastId;
            return $item;
        });

        $newData->forget(0);

        $user->entries()->createMany($newData->toArray());

        return $entry;
    }

    protected function saveGeneral(array $data): Entry
    {
        $data['bank_account_id'] = null;
        $data['credit_card_id'] = null;
        $data['total_parcel'] = $data['parcel'];

        $user = auth()->user();
        
        if ($data['parcel'] > 1) {
            $due_date = $data['due_date'];
            $amountParcel = round($data['amount'] / $data['total_parcel'], 2);
            $difference = round(($amountParcel * $data['total_parcel']) - $data['amount'], 2);

            $newArr = collect();
            for ($i = 1; $i <= $data['total_parcel']; ++$i) {
                $data['parcel'] = $i;
                $data['amount'] = $i === (int) $data['total_parcel'] ? $amountParcel - $difference : $amountParcel;
                $newArr->push($data);
                $data['due_date'] = $due_date->copy()->addMonthNoOverflow($i);
            }    
            
            $entry = $user->entries()->create($newArr->first());
            $lastId = $entry->id;
            $entry->parent_id = $lastId;
            $entry->save();

            $newData = $newArr->map(function ($item) use ($lastId) {
                $item['parent_id'] = $lastId;
                return $item;
            });

            $newData->forget(0);

            $user->entries()->createMany($newData->toArray());
            
        
            return $entry;
        }

        return $user->entries()->create($data);
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

        // Verificar se foi alterado o total de parcelas para recalcular o valor de cada parcela
        if ($data['total_parcel'] > 1) {
            $data['sequence'] = $entry->sequence;
            $due_date = $data['due_date'];
            $amountParcel = round($data['amount'] / $data['total_parcel'], 2);
            $difference = round(($amountParcel * $data['total_parcel']) - $data['amount'], 2);

            $newArr = collect();
            for ($i = 1; $i <= $data['total_parcel']; ++$i) {
                $data['parcel'] = $i;
                $data['amount'] = $i === (int) $data['total_parcel'] ? $amountParcel - $difference : $amountParcel;                
                $newArr->push($data);
                $data['due_date'] = $due_date->copy()->addMonthNoOverflow($i);
            }

            // dd($newArr->forget(0));
            // Atualiza o ID primario da despesa
            $entry->update($newArr->first());
            $newArr->forget(0);

            // Cria novos registros com as novas parcelas
            $user = auth()->user();
            $user->entries()->createMany($newArr->toArray());

            return $entry;
        }

        $entry->update($data);

        return $entry;
    }

    public function payday(array $data, string $id): int
    {
        if (isset($data['reference'])) {
            $arr = explode("-", $data['reference']);   
            return Entry::where('credit_card_id', (int) $id)
                ->whereMonth('due_date', $arr[1])
                ->whereYear('due_date', $arr[0])
                ->update([
                    'payday' => $data['payday']
                ]);
        }

        $entry = $this->find($id);

        return $entry->update($data);
    }

    public function delete(string $id): bool
    {
        $entry = $this->find($id);

        return $entry->delete();
    }
}
