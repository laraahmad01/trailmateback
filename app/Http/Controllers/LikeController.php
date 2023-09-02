<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Like;


class LikeController extends Controller
{
    public function getLikesForPost($postId)
    {
        $likes = Like::where('post_id', $postId)->pluck('user_id'); 

        return response()->json($likes);
    }
    public function addLikeToPost(Request $request, $postId) {
        $userId = $request->user_id; // Assuming you pass user_id in the request
        
        $like = new Like();
        $like->user_id = $userId;
        $like->post_id = $postId;
        $like->save();
        
        return response()->json(['message' => 'Like added successfully']);
    }

    public function removeLikeFromPost($postId, $likeId) {
        $like = Like::find($likeId);
        if (!$like) {
            return response()->json(['message' => 'Like not found'], 404);
        }
        
        $like->delete();
        
        return response()->json(['message' => 'Like removed successfully']);
    }
    public function getLikesNames($postId)
{
    try {
        $likesNames = DB::table('likes')
            ->join('users', 'users.id', '=', 'likes.user_id')
            ->where('likes.post_id', $postId)
            ->select('users.firstname', 'users.lastname')
            ->get();

        return response()->json($likesNames);
    } catch (\Exception $e) {
        // Log the error for debugging purposes
        Log::error('Error fetching likes names:', ['error' => $e->getMessage()]);

        // Return an error response
        return response()->json(['error' => 'An error occurred while fetching likes names.'], 500);
    }
}
}
