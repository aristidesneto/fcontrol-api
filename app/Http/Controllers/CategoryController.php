<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        return CategoryResource::collection($this->categoryService->list($request->all()));
    }

    public function store(CategoryRequest $request)
    {
        return response()->json($this->categoryService->store($request->all()), 201);
    }

    public function update(CategoryRequest $request, string $id)
    {
        return response()->json($this->categoryService->update($request->all(), (int) $id), 200);
    }

    public function destroy(string $id)
    {
        return response()->json($this->categoryService->delete((int) $id), 200);
    }
}
