<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Like;


class LikeController extends Controller
{
public function getPostLikes($postId)
{
    try {
        $likes = Like::where('post_id', $postId)->get();
        return response()->json($likes);
    } catch (\Exception $e) {
        // Log the error for debugging purposes
        Log::error('Error fetching post likes:', ['error' => $e->getMessage()]);

        // Return an error response
        return response()->json(['error' => 'An error occurred while fetching post likes.'], 500);
    }
}
public function deleteLike($postId)
{
    try {
        // Get the authenticated user's ID
        // $userId = Auth::id();
        $userId =  Auth::id();

        // Find the like record based on the post_id and user_id
        $like = Like::where('post_id', $postId)
            ->where('user_id', $userId)
            ->first();

        if (!$like) {
            // Like record not found, return a response indicating that it doesn't exist
            return response()->json(['message' => 'Like not found'], 404);
        }

        // Delete the like record
        $like->delete();

        return response()->json(['message' => 'Like deleted successfully']);
    } catch (\Exception $e) {
        // Handle any errors that occur during the deletion process
        return response()->json(['message' => 'Error deleting like', 'error' => $e->getMessage()], 500);
    }
}

public function countLikes($postId)
    {
        // Query the database to count likes for the given post_id
        $likeCount = Like::where('post_id', $postId)->count();

        return response()->json(['count' => $likeCount]);
    }

public function addLike(Request $request)
    {
        try {
            // Assuming you pass user_id and post_id in the request
            $userId =  Auth::id();
            $postId = $request->input('post_id');
            
            // Check if the user has already liked the post
            $existingLike = Like::where('user_id', $userId)
                                ->where('post_id', $postId)
                                ->first();
    
            if ($existingLike) {
                return response()->json(['message' => 'User already liked this post']);
            }
    
            // Create a new like if the user hasn't already liked the post
            $like = new Like;
            $like->user_id = $userId;
            $like->post_id = $postId;
            date_default_timezone_set('Asia/Beirut');
            $like->created_at = date('Y-m-d H:i:s'); // Assuming you send the like content in the request
    
            $like->save();
    
            return response()->json(['message' => 'Like added successfully', 'like' => $like]);
        } 
        catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error adding like:', ['error' => $e->getMessage()]);
    
            // Return an error response
            return response()->json(['error' => 'An error occurred while adding a like.'], 500);
        }
    }
    
public function getLikesByUserAndPost($post_id, $user_id)
    {
        try {
            // Query the likes table to find likes for the specified user and post
            $likes = Like::where('post_id', $post_id)
                         ->where('user_id', $user_id)
                         ->get();
            
            return response()->json(['likes' => $likes]);
        } catch (\Exception $e) {
            // Handle any errors that occur during the retrieval process
            return response()->json(['error' => 'An error occurred while fetching likes.'], 500);
        }
    }
    
public function checkIfUserLikedPost($userId, $postId) {
        $like = Like::where('user_id', $userId)
                    ->where('post_id', $postId)
                    ->first();
    
        return response()->json(['liked' => $like ? true : false]);
    }
    


}
