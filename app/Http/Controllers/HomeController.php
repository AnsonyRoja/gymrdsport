<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $datos = 'datos del backend';
        $edad = 25;

        return view('example', compact('datos', 'edad'));
    }
}
