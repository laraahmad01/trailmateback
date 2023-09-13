<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Community;


class PostController extends Controller
{
    public function index() {
        $posts = Post::with('user:id,firstname,lastname', 'community:name')->get();
        return response()->json($posts);
    }
    

    public function store(Request $request)
    {
        // Validate the incoming request data (you can adjust validation rules)
        $locationData = [
            'latitude' => 123.456,
            'longitude' => 789.012,
            'city' => 'Example City',
        ];
        $cityName = $locationData['city'];
        
        $post = new Post;
        $post->community_id = $request->input('community_id');
        $post->user_id = 3;
        $post->image_url = null;
        $post->description = $request->input('description');
        date_default_timezone_set('Asia/Beirut');
        $post->date = date('Y-m-d H:i:s');
                $post->location = json_encode($locationData); 
        $post->person_tag = 'lara'; 

        $post->save();
        return response()->json(['message' => 'post created successfully', 'post' => $post]);
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
