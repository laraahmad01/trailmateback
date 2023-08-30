<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() {
        $posts = Post::with('user:id,firstname,lastname', 'community:name')->get();
        return response()->json($posts);
    }
    

    public function store(Request $request)
    {
        $post = Post::create($request->all());
        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return response()->json($post, 200);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(null, 204);
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
