<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        $users = User::with('elephants')->where('id', '!=', Auth::user()->id)->get();

        return response()->json($users);
    }
}
