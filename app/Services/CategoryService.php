<?php

namespace App\Services;

use App\Models\Category;
use App\Http\Resources\CategoryResource;

class CategoryService
{
    public function list(array $data)
    {
        $paginate = $data['total_page'] ?? 10;
        if (isset($data['sort_by'])) {
            $key = $data['sort_by']['key'] ?? 'name';
            $order = $data['sort_by']['order'] ?? 'asc';
        }
        
        $query = Category::orderby($key ?? 'name', $order ?? 'asc');

        if (isset($data['status']) && $data['status'] !== 'all') {
            $query->where('status', $data['status']);
        }

        if (isset($data['type']) && $data['type'] !== 'all') {
            $type = $data['type'] === 'expense' ? 'expense' : 'income';
            $query->where('type', $type);
        }

        if (isset($data['paginate']) && $data['paginate'] == false) {
            return $query->get();
        }

        return $query->paginate($paginate);
    }

    public function store(array $data): array
    {
        $category = Category::create($data);

        return [
            "message" => "Categoria cadastrada com sucesso",
            "data" => new CategoryResource($category)
        ];
    }

    public function update(array $data, int $id): array
    {
        $data['status'] = boolval($data['status']);

        $category = Category::where('id', $id)->first()->update($data);

        return [
            "message" => "Categoria atualizada com sucesso",
            "data" => $category,
        ];
    }

    public function delete(string $id): ?bool
    {
        $category = Category::with('entries')->find((int) $id);

        if (! $category) {
            abort(404, "Registro não encontrado");
        }

        if ($category->entries->count() > 0) {
            abort(422, "Erro! Essa categoria não pode ser removida, pois está sendo usada em um lançamento");            
        }

        return $category->delete();
    }
}