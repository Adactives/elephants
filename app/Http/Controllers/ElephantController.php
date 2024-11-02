<?php

namespace App\Http\Controllers;

use App\Models\Elephant;
use Illuminate\Http\Request;

class ElephantController extends Controller
{
    public function index()
    {
        return Elephant::all();
    }

    public function search(Request $request)
    {
        $request->validate([
           'searchValue' => 'nullable|string|max:255',
           'targetField' => 'required|string|in:name,description,id',
       ]);

        $searchValue= $request->input('searchValue');
        $targetField = $request->input('targetField', 'name');

        // Build the query based on search type
        return Elephant::query()->where($targetField, 'LIKE', '%' . $searchValue . '%')->get();
    }
}
