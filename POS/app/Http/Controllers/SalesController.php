<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function show(string $month, string $date)
    {
        return view('sales', ['month' => $month, 'date' => $date]);
    }
}
