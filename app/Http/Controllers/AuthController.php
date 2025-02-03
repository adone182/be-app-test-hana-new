<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Login
    public function login(Request $request)
    {
        Log::info('Login attempt', ['request' => $request->all()]);  // Mencatat data request yang diterima

        $validator = Validator::make($request->all(), [
            'user_email' => 'required|email|exists:users,user_email',
            'user_password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            Log::error('Login failed - Validation error', ['errors' => $validator->errors()]);  // Mencatat error validasi
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $user = User::where('user_email', $request->user_email)->first();

        if (!$user || !Hash::check($request->user_password, $user->user_password)) {
            Log::error('Login failed - Invalid credentials', ['email' => $request->user_email]);  // Mencatat login gagal karena kredensial salah
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('YourApp')->plainTextToken;

        Log::info('Login successful', ['user_id' => $user->id, 'token' => $token]);  // Mencatat login berhasil

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

        Log::info('User logged out', ['user_id' => $user->id]);  // Mencatat log keluar

        return response()->json(['message' => 'Logout successful'], 200);
    }
}
