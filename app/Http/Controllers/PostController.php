<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Log;
use App\Models\Community;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Middleware\Authenticate;


class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }


    public function store(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();

            $locationData = $request->input('locationData', [
                'latitude' => 0.0,
                'longitude' => 0.0,
                'city' => 'Unknown City',
            ]);

            $post = new Post();
            $post->community_id = $request->input('community_id');
            $post->user_id = $user->id;
            $post->image_url = $request->input('image_url', null);
            $post->description = $request->input('description');
            $post->date = now();
            $post->location = json_encode($locationData);

            $post->save();

            return response()->json(['message' => 'post created successfully', 'post' => $post]);

        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }



    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post);
    }


    public function update(Request $request, $id)
    {
        // Validate the incoming request data (you can adjust validation rules)
        // $request->validate([
        //   'description' => 'required|string|max:255', // Adjust validation rules as needed
        //]);

        try {
            $post = Post::findOrFail($id);

            // You can update only the fields that need to be updated
            $post->description = $request->input('description');

            // Save the updated post
            $post->save();

            return response()->json(['message' => 'Post updated successfully', 'post' => $post]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating post', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        // Find the post by ID
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Delete related likes and comments
        $post->likes()->delete();
        $post->comments()->delete();

        // Delete the post itself
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
    public function getCommunityPosts($communityId)
    {
        try {
            $posts = Post::where('community_id', $communityId)->get();
            return response()->json($posts);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error fetching community posts:', ['error' => $e->getMessage()]);

            // Return an error response
            return response()->json(['error' => 'An error occurred while fetching community posts.'], 500);
        }
    }
}