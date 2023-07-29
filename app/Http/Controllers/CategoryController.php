<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
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
        $paginate = $request->total_page ?? 10;
        
        $query = Category::orderBy('type')->orderby('name');

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->type && $request->type !== 'all') {
            $type = $request->type === 'expense' ? 'expense' : 'income';
            $query->where('type', $type);
        }

        return CategoryResource::collection($query->paginate($paginate));
    }

    public function store(Request $request)
    {
        return response()->json($this->categoryService->store($request->all()), 200);
    }

    public function update(Request $request, string $id)
    {
        return response()->json($this->categoryService->update($request->all(), (int) $id), 200);
    }

    public function destroy(string $id)
    {
        return response()->json($this->categoryService->delete((int) $id), 200);
    }
}
