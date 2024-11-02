<?php

namespace App\Http\Controllers;

use App\Models\Elephant;

class ElephantController extends Controller
{
    public function index()
    {
        return Elephant::all();
    }
}
