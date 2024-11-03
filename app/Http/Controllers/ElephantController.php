<?php

namespace App\Http\Controllers;

use App\Models\Elephant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ElephantController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        return response()->json(Elephant::all());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function search(Request $request) : JsonResponse
    {
        $request->validate([
           'query' => 'required|string|max:255',
       ]);

        $query = $request->input('query', '');

        $elephants = Elephant::where('name', 'LIKE', "%{$query}%")
         ->orWhere('description', 'LIKE', "%{$query}%")
         ->get();

        return response()->json($elephants);
    }
}
