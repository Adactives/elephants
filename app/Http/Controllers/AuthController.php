<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => Hash::make($request->password),
         ]);

        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'message' => 'Account registered.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();

        $existingToken = $user->tokens()->first();
        if ($existingToken) {
            return response()->json([
                'message' => 'Already logged in.',
            ]);
        }

        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        // Invalidate the current token
        $user->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout successful.',
        ]);
    }
}
