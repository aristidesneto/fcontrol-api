<?php

namespace App\Http\Controllers;

use App\Http\Resources\CreditCardResource;
use App\Models\CreditCard;
use Illuminate\Http\Request;

class CreditCardController extends Controller
{
    public function index()
    {
        return CreditCardResource::collection(CreditCard::orderBy('name')->get());
    }
}
