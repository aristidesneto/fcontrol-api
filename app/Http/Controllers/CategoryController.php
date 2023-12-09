<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreUpdateRequest;
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

    public function all(Request $request)
    {
        return CategoryResource::collection($this->service->all($request->all()));
    }

    public function index(Request $request)
    {
        return CategoryResource::collection($this->service->list($request->all()));
    }

    public function store(CategoryStoreUpdateRequest $request)
    {
        $category = $this->service->store($request->all());

        return (new CategoryResource($category))->response()->setStatusCode(201);
    }

    public function update(CategoryStoreUpdateRequest $request, string $id)
    {
        $category = $this->service->update($request->all(), $id);

        return response()->json($category);
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);

        return response()->json([], 204);
    }
}
