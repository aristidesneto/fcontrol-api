<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryService $service;

    public function __construct(CategoryService $categoryService)
    {
        $this->service = $categoryService;
    }

    public function index(Request $request)
    {
        return CategoryResource::collection($this->service->list($request->all()));
    }

    public function store(CategoryRequest $request)
    {
        return response()->json($this->service->store($request->all()), 201);
    }

    public function update(CategoryRequest $request, string $id)
    {
        return response()->json($this->service->update($request->all(), (int) $id), 200);
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);

        return response()->json([], 204);
    }
}
