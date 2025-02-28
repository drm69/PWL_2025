<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        return view('product');
    }
    public function show(string $id)
    {
        return view('category.' .$id);
    }
}
