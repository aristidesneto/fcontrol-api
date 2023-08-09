<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntryRequest;
use App\Models\Entry;
use Illuminate\Http\Request;
use App\Services\EntryService;
use App\Http\Resources\EntryResource;
use Illuminate\Support\Facades\DB;

class EntryController extends Controller
{
    protected EntryService $entryService;

    public function __construct(EntryService $entryService)
    {
        $this->entryService = $entryService;
    }
    
    public function index(Request $request)
    {
        return EntryResource::collection($this->entryService->list($request->all()));
    }

    public function show(string $id)
    {
        return response()->json($this->entryService->findById((int) $id), 200);
    }

    public function store(EntryRequest $request)
    {
        return response()->json($this->entryService->store($request->all()), 201);
    }

    public function update(EntryRequest $request, string $id)
    {
        return response()->json($this->entryService->update($request->all(), (int) $id), 200);
    }

    public function destroy(string $id)
    {
        return response()->json($this->entryService->delete((int) $id), 200);
    }
}
