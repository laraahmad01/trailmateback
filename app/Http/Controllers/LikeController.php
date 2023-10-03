<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Log;

class LikeController extends Controller
{
    public function getPostLikes($postId)
    {
        try {
            $likes = Like::where('post_id', $postId)->get();
            return response()->json($likes);
        } catch (\Exception $e) {
            Log::error('Error fetching post likes:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching post likes.'], 500);
        }
    }

    public function deleteLike($postId)
    {
        try {
            $userId = Auth::id();
            $like = Like::where('post_id', $postId)
                ->where('user_id', $userId)
                ->first();

            if (!$like) {
                return response()->json(['message' => 'Like not found'], 404);
            }

            $like->delete();
            return response()->json(['message' => 'Like deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting like', 'error' => $e->getMessage()], 500);
        }
    }

    public function countLikes($postId)
    {
        $likeCount = Like::where('post_id', $postId)->count();
        return response()->json(['count' => $likeCount]);
    }

    public function addLike(Request $request)
    {
        try {
            $userId = Auth::id();
            $postId = $request->input('post_id');
            $existingLike = Like::where('user_id', $userId)
                ->where('post_id', $postId)
                ->first();

            if ($existingLike) {
                return response()->json(['message' => 'User already liked this post']);
            }

            $like = new Like;
            $like->user_id = $userId;
            $like->post_id = $postId;
            date_default_timezone_set('Asia/Beirut');
            $like->created_at = date('Y-m-d H:i:s');
            $like->save();

            return response()->json(['message' => 'Like added successfully', 'like' => $like]);
        } catch (\Exception $e) {
            Log::error('Error adding like:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while adding a like.'], 500);
        }
    }

    public function getLikesNamesForPost($postId)
    {
        try {
            $likes = Like::where('post_id', $postId)->get();
            $userNames = [];

            foreach ($likes as $like) {
                $user = User::find($like->user_id);
                if ($user) {
                    $userNames[] = [
                        'name' => $user->firstname . ' ' . $user->lastname,
                        'image_url' => $user->image_url,
                        // Include the image_url in the response
                    ];
                }
            }

            return response()->json(['userNames' => $userNames]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching user names and image URLs.'], 500);
        }
    }

}