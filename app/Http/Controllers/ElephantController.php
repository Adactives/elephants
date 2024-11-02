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
           'searchTerm' => 'nullable|string|max:255',
           'targetField' => 'required|string|in:name,description,both',
       ]);

        $term = $request->input('term');
        $type = $request->input('type', 'both'); // default to 'both' if no type is provided

        // Build the query based on search type
        return Elephant::query()
           ->when($type === 'name', function ($query) use ($term) {
               $query->where('name', 'LIKE', "%{$term}%");
           })
           ->when($type === 'description', function ($query) use ($term) {
               $query->where('description', 'LIKE', "%{$term}%");
           })
           ->when($type === 'both', function ($query) use ($term) {
               $query->where(function ($query) use ($term) {
                   $query->where('name', 'LIKE', "%{$term}%")
                         ->orWhere('description', 'LIKE', "%{$term}%");
               });
           })->get();
    }
}
