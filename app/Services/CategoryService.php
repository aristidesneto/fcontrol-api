<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function store(array $data)
    {
        Category::create($data);

        return [
            "status" => "success",
            "message" => "Income created successfully",
        ];
    }

    public function update(array $data, int $id)
    {
        $data['status'] = $data['status'] ? 'active' : 'inactive';
        $category = Category::find($id)->update($data);

        return [
            "message" => "Update successfully",
            "data" => $category,
        ];
    }

    public function delete(int $id)
    {
        $category = Category::find($id);

        if ($category->delete()) {
            return [
                "status" => "success",
                "message" => "Income removed successfully"
            ];
        }

        return [
            "status" => "error",
            "message" => "Error to remove category"
        ];
    }
}