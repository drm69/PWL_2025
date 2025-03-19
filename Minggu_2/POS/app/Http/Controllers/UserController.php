<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(string $id, string $name)
    {
        return view('user', ['id' => $id, 'name' => $name]);
    }
}
