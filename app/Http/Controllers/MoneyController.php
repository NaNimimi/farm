<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Money;

class MoneyController extends Controller
{
    public function showCashBox()
    {
        try {
            $cashbox = Money::with(['customer', 'doctor', 'service'])->get();
            return response()->json($cashbox, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
