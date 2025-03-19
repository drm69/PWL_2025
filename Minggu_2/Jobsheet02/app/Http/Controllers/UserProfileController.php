<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show(){
        $url = route('profile'); 
        return redirect()->route('profile');
    }
}
