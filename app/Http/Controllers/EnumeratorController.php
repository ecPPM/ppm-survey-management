<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnumeratorController extends Controller
{
    public function info()
    {
        return view('info');
    }

    public function registration()
    {
        return view('registration');
    }

    public function deployment()
    {
        return view('info');
    }
}
