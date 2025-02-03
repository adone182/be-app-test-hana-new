<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Login
    public function login(LoginRequest $request)
    {
        Log::info('Login attempt', ['request' => $request->all()]);

        $user = User::where('user_email', $request->user_email)->first();

        if (!$user || !Hash::check($request->user_password, $user->user_password)) {
            Log::error('Login failed - Invalid credentials', ['email' => $request->user_email]); 
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('YourApp')->plainTextToken;

        Log::info('Login successful', ['user_id' => $user->id, 'token' => $token]); 

        return response()->json([
            'message' => 'Login successful',
            'token' => $token
        ], 200);
    }

    // Logout
    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens->each(function ($token) {
            $token->delete();
        });

        Log::info('User logged out', ['user_id' => $user->id]); 

        return response()->json(['message' => 'Logout successful'], 200);
    }
}

