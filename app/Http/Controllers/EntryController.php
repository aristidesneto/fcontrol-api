<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EntryService;
use App\Http\Requests\EntryRequest;
use App\Http\Resources\EntryResource;
use App\Http\Requests\EntryPaydayRequest;

class EntryController extends Controller
{
    protected EntryService $service;

    public function __construct(EntryService $service)
    {
        $this->service = $service;
    }
    
    public function index(Request $request)
    {
        return EntryResource::collection($this->service->list($request->all()));
    }

    public function show(string $id)
    {
        return new EntryResource($this->service->show($id));
    }

    public function store(EntryRequest $request)
    {
        return EntryResource::make($this->service->store($request->all()))
            ->response($request)
            ->setStatusCode(201);
    }

    public function update(EntryRequest $request, string $id)
    {
        return new EntryResource($this->service->update($request->all(), $id));
    }

    public function payday(EntryPaydayRequest $request, string $id)
    {
        $this->service->payday($request->only(['payday', 'observation', 'reference']), $id);

        return response()->json([], 200);
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);

        return response()->json([], 204);
    }
}
