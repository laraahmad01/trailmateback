<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = new User([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => "user",
        ]);

        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'firstname' => $user->firstname,
            'email' => $user->email,
            'message' => 'Registration successful',
            'code' => 201,
            'token' => $token,
        ]);

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'code' => 404,
            ]);
        }

        if (password_verify($request->password, $user->password)) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'email' => $user->email,
                'firstname' => $user->firstname,
                'message' => 'Login successful',
                'code' => 200,
                'token' => $token,
            ]);
        }

        return response()->json([
            'message' => 'Invalid login credentials',
            'code' => 401,
        ]);
    }
    public function logout(Request $request)
    {
        // Revoke the current user's token, effectively logging them out
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful',
            'code' => 200,
        ]);
    }

    public function getUserId(Request $request)
    {
        $userId = Auth::user();
        return response()->json(['user_id' => $userId, 'message' => 'User ID retrieved successfully']);
    }
}