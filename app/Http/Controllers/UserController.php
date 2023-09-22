<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function show()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'code' => 404,
            ]);
        }

        return response()->json([
            'id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'country' => $user->country,
            'city' => $user->city,
            'image_url' => $user->image_url,
            'role' => $user->role,
        ]);
    }

    public function getAllUsers()
    {
        $users = User::all();

        return response()->json($users);
    }

    public function getSignedInUser()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function updateUserInfo(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'image_url' => 'nullable|string',
        ]);

        // Get the authenticated user
        $user = auth()->user();

        // Update the user's information
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->country = $request->input('country');
        $user->city = $request->input('city');
        $user->image_url = $request->input('image_url');
        $user->save();

        return response()->json([
            'message' => 'User information updated successfully',
            'user' => $user,
        ]);
    }

}