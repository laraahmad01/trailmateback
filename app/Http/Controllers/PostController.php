<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'community_id' => 'required',
            'user_id' => 'required',
            'post_type' => 'required|in:text,image,location,date,tag',
            'content' => 'required',
            'image_url' => 'nullable',
            'description' => 'required',
            'date' => 'nullable',
            'location' => 'nullable',
            'person_tag' => 'nullable|exists:users,username',
        ]);

        $post = Post::create($validatedData);

        return response()->json(['message' => 'Post created successfully', 'post' => $post]);
    }
    public function getAllPosts()
    {
        $posts = Post::all();

        return response()->json(['posts' => $posts]);
    }

    public function getPost($postId)
    {
        $post = Post::findOrFail($postId);

        return response()->json(['post' => $post]);
    }
    public function update(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);

        $validatedData = $request->validate([
            'community_id' => 'required',
            'user_id' => 'required',
            'post_type' => 'required|in:text,image,location,date,tag',
            'content' => 'required',
            'image_url' => 'nullable',
            'description' => 'required',
            'date' => 'nullable',
            'location' => 'nullable',
            'person_tag' => 'nullable|exists:users,username',
        ]);

        $post->update($validatedData);

        return response()->json(['message' => 'Post updated successfully', 'post' => $post]);
    }
    public function delete($postId)
    {
        $post = Post::findOrFail($postId);
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
