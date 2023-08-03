<?php

namespace App\Services;

use App\Models\Category;
use App\Http\Resources\CategoryResource;

class CategoryService
{
    public function list(array $data)
    {
        $paginate = $data['total_page'] ?? 10;
        
        $query = Category::orderBy('type')->orderby('name');

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

        $category = Category::find($id)->update($data);

        return [
            "message" => "Categoria atualizada com sucesso",
            "data" => $category,
        ];
    }

    public function delete(int $id)
    {
        $category = Category::find($id)->delete();

        if ($category) {
            return [
                "message" => "Categoria removida com sucesso",
                "data" => []
            ];
        }
    }
}