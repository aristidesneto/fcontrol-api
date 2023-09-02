<?php

namespace App\Http\Controllers;

use App\Http\Resources\CreditCardResource;
use App\Services\CreditCardService;
use Illuminate\Http\Request;

class CreditCardController extends Controller
{
    protected CreditCardService $service;

    public function __construct(CreditCardService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return CreditCardResource::collection($this->service->list($request->all()));
    }

    public function show(Request $request, string $id)
    {
        return response()->json($this->service->calculate_next_due_date($request->calculate_due_date, $id), 200);
    }
}
