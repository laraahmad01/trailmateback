<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    public function create(Request $request)
{
   
        $obj = new User();
        
        $obj->firstname = 'samar';
        $obj->lastname='ahmad';
        $obj->email = 'samar@mail.com';
        $obj->email_verified_at = now();
        $obj->password = bcrypt('password123');
        $obj->role = 'admin';
        $obj->created_at = now()->subDays(10);
        
        $obj->save();
        
        return 'ok';    
}
public function getUser($id)
{
    $user = User::find($id);
    
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }
    
    return response()->json($user);
}

public function getAllUsers()
{
    $users = User::all();
    
    return response()->json($users);
}
}
