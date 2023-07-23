<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->type) {
            $type = $request->type === 'expense' ? 'expense' : 'income';

            return CategoryResource::collection(Category::where('type', $type)->orderBy('name')->paginate());
        }

        return CategoryResource::collection(Category::orderBy('name')->paginate());
    }
}
