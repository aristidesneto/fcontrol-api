<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class CategoryService
{
    protected $paginate = Category::PAGINATE;

    public function all(array $data): Collection
    {
        // $data['type'] = 'all';

        return $this->queryApi($data)
            ->get();
    }

    public function list(array $data): LengthAwarePaginator
    {
        $paginate = Arr::get($data, 'total_page', $this->paginate);

        return $this->queryApi($data)
            ->paginate($paginate);
    }

    protected function queryApi(?array $data = []): Builder
    {
        $key = Arr::get($data, 'sort_by.key', 'name');
        $order = Arr::get($data, 'sort_by.order', 'asc');
        
        $query = Category::orderby($key, $order);

        $status = Arr::get($data, 'status');

        $type = Arr::get($data, 'type');

        if (! in_array($type, array_merge(Category::listTypes(), ['all']))) {
            abort(422, 'Type invalid');
        }

        $query->when(
            $status !== null,
            fn ($query) => $query->where('status', $status)
        );

        $query->when(
            $type !== 'all',
            fn ($query) => $query->where('type', $type)
        );

        return $query;
    }

    public function store(array $data): Category
    {
        $category = Category::create($data);

        return $category;
    }

    public function update(array $data, string $id): Category
    {
        $category = $this->find($id);

        $category->update($data);

        return $category;
    }

    public function delete(string $id): ?bool
    {
        $category = $this->find($id); 
        
        if ($category->entries->count() > 0) {
            abort(422, "Erro! Essa categoria não pode ser removida, pois está sendo usada em um lançamento");            
        }

        return $category->delete();
    }

    public function find(string $id): Category
    {
        $category = Category::find((int) $id);
        
        abort_if($category === null, 404);

        return $category;
    }
}