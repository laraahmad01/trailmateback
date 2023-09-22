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
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->likes()->delete();
        $post->comments()->delete();

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
    public function getCommunityPostsData($communityId)
    {
        try {
            $authenticatedUserId = auth()->user()->id;

            $communityPostsData = Post::where('community_id', $communityId)
                ->with('user')
                ->leftJoin('likes', function ($join) use ($authenticatedUserId) {
                    $join->on('posts.id', '=', 'likes.post_id')
                        ->where('likes.user_id', '=', $authenticatedUserId);
                })
                ->select('posts.*', 'likes.id as liked')
                ->withCount('likes')
                ->withCount('comments')
                ->get();

            $community = Community::findOrFail($communityId);
            $communityName = $community->name;
            $adminId = $community->admin_id;

            return response()->json([
                'communityPostsData' => $communityPostsData,
                'communityName' => $communityName,
                'adminId' => $adminId,
                'authenticatedUserId' => $authenticatedUserId,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching community posts data:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching community posts data.'], 500);
        }
    }

    public function getUserPostsInPublicCommunities()
    {
        try {
            $authenticatedUserId = auth()->user()->id;

            // Get all posts of the authenticated user in public communities
            $userPosts = Post::whereHas('community', function ($query) {
                $query->where('is_private', false); // Only public communities
            })
                ->where('user_id', $authenticatedUserId)
                ->get();

            return response()->json(['userPosts' => $userPosts]);
        } catch (\Exception $e) {
            Log::error('Error fetching user posts in public communities:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching user posts.'], 500);
        }
    }


    public function getUserPhotosInPublicCommunities()
    {
        try {
            $authenticatedUserId = auth()->user()->id;

            // Get all photos posted by the authenticated user in public communities
            $userPhotos = Post::whereHas('community', function ($query) {
                $query->where('visibility', 'public'); // Only public communities
            })
                ->where('user_id', $authenticatedUserId)
                ->whereNotNull('image_url') // Ensure there's an image URL
                ->get();

            return response()->json(['userPhotos' => $userPhotos]);
        } catch (\Exception $e) {
            Log::error('Error fetching user photos in public communities:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching user photos.'], 500);
        }
    }



}