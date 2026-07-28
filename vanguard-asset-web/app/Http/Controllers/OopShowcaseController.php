<?php

namespace App\Http\Controllers;

class OopShowcaseController extends Controller
{
    /**
     * Display the interactive OOP showcase page.
     */
    public function index()
    {
        return view('oop-showcase');
    }
}
