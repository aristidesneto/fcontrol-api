<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;
use App\Services\EntryService;
use App\Http\Resources\EntryResource;

class EntryController extends Controller
{
    protected EntryService $entryService;

    public function __construct(EntryService $entryService)
    {
        $this->entryService = $entryService;
    }
    
    public function index(Request $request)
    {
        $query = Entry::with('category')->orderBy('due_date');

        if ($request->type) {
            $type = $request->type === 'expense' ? 'expense' : 'income';
            $query->where('type', $type);
        }

        return EntryResource::collection($query->paginate());    
    }

    public function store(Request $request)
    {
        return response()->json($this->entryService->store($request->all()), 201);
    }
}
