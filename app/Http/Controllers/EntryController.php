<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EntryService;
use App\Http\Requests\EntryRequest;
use App\Http\Resources\EntryResource;
use App\Http\Requests\EntryPaydayRequest;

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
        return response()->json($this->entryService->show($id), 200);
    }

    public function store(EntryRequest $request)
    {
        return response()->json($this->entryService->store($request->all()), 201);
    }

    public function update(EntryRequest $request, string $id)
    {
        return response()->json($this->entryService->update($request->all(), $id), 200);
    }

    public function payday(EntryPaydayRequest $request, string $id)
    {
        return response()->json($this->entryService->payday($request->only(['payday', 'observation']), $id), 200);
    }

    public function destroy(string $id)
    {
        return response()->json($this->entryService->delete($id), 200);
    }
}
