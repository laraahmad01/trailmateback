<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProfilePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilePostController extends Controller
{
    public function showUserPhotos()
    {
        $user = Auth::user();

        $photos = ProfilePost::where('user_id', $user->id)->get();

        return response()->json(['photos' => $photos]);
    }

    public function showUserPhotosById($userId)
    {
        $photos = ProfilePost::where('user_id', $userId)->get();

        return response()->json(['photos' => $photos]);
    }

    public function storePhoto(Request $request)
    {
        $request->validate([
            'image_url' => 'required|string',
        ]);

        $user = Auth::user();

        ProfilePost::create([
            'user_id' => $user->id,
            'image_url' => $request->image_url,
        ]);

        return redirect()->back()->with('success', 'Photo uploaded successfully');
    }
}