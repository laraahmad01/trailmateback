<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Community;
use App\Models\User;
use App\Models\CommunityMember;


class CommunityController extends Controller
{
    public function index()
    {
        $communities = Community::all();
        return response()->json($communities);
    }

    public function formSubmit(Request $request)
    {
        //  $community = Community::create($request->all());
        return response()->json([$request->all()]);
    }

    public function show($id)
    {
        $community = Community::findOrFail($id);
        return response()->json($community);
    }

    public function update(Request $request, $id)
    {
        $community = Community::findOrFail($id);

        if ($community->admin_id !== auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'description' => 'string|max:255',
            'image_url' => 'nullable|string',
        ]);

        $community->update($validatedData);

        return response()->json(['message' => 'Community updated successfully', 'community' => $community], 200);
    }

    public function delete($id)
    {
        try {
            $user = Auth::user();

            $community = Community::findOrFail($id);

            if ($user->id !== $community->admin_id) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            foreach ($community->posts as $post) {
                $post->likes()->delete();
                $post->comments()->delete();
            }

            $community->posts()->delete();
            $community->members()->delete();
            $community->delete();

            return response()->json(['message' => 'Community and related records deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting community', 'error' => $e->getMessage()], 500);
        }
    }


    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'visibility' => 'nullable|string|in:public,private',
        ]);

        $adminId = auth()->user()->id;

        $community = new Community;
        $community->name = $validatedData['name'];
        $community->description = $validatedData['description'];
        $community->image_url = $validatedData['image_url'] ?? null;
        \Log::info('Image URL: ' . $community->image_url);

        $community->admin_id = $adminId;
        $community->visibility = $validatedData['visibility'] ?? 'public';
        $community->save();

        $communityMember = new CommunityMember;
        $communityMember->community_id = $community->id;
        $communityMember->member_id = $adminId;
        $communityMember->is_admin = 1;
        $communityMember->muted = 0;
        $communityMember->save();

        return response()->json([
            'message' => 'Community created successfully',
            'community' => $community,
            'image_url' => $community->image_url,
        ]);
    }

    public function addAdmin(Community $community, Request $request)
    {
        $userId = auth()->user()->id;

        if (!$community->admins->contains($userId)) {
            $community->admins()->attach($userId);

            return response()->json(['message' => 'User added as an admin'], 200);
        } else {
            return response()->json(['message' => 'User is already an admin'], 400);
        }
    }

    public function userCommunities()
    {
        $userId = auth()->user()->id;

        $userCommunities = Community::whereHas('members', function ($query) use ($userId) {
            $query->where('member_id', $userId);
        })->orWhereHas('admins', function ($query) use ($userId) {
            $query->where('member_id', $userId); // Update 'user_id' to 'member_id' here
        })->get();

        return response()->json($userCommunities);
    }

    public function showWithMembersAndImages($id)
    {
        try {
            $authenticatedUserId = Auth::user()->id; 

            $community = Community::findOrFail($id);

            $membersCount = CommunityMember::where('community_id', $id)->count();

            $images = Post::where('community_id', $id)
                ->whereNotNull('image_url')
                ->pluck('image_url')
                ->toArray();

            return response()->json([
                'authenticated_user_id' => $authenticatedUserId,
                'community' => $community,
                'members_count' => $membersCount,
                'images' => $images,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching community data:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching community data.'], 500);
        }
    }

}