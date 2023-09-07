<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Community;
use App\Models\User;


class CommunityController extends Controller
{
    public function index()
    {
        $communities = Community::all();
        return response()->json($communities);
    }

    public function store(Request $request)
    {
        $community = Community::create($request->all());
        return response()->json($community, 201);
    }

    public function show($id)
    {
        $community = Community::findOrFail($id);
        return response()->json($community);
    }

    public function update(Request $request, $id)
    {
        $community = Community::findOrFail($id);
        $community->update($request->all());
        return response()->json($community, 200);
    }

    public function destroy($id)
    {
        $community = Community::findOrFail($id);
        $community->delete();
        return response()->json(null, 204);
    }

    public function deleteCommunity($communityId)
    {
        // Find the community
        $community = Community::find($communityId);

        if (!$community) {
            return response()->json(['error' => 'Community not found'], 404);
        }

        // Delete likes and comments related to posts in this community
        $postIds = $community->posts->pluck('id')->toArray();
        Like::whereIn('post_id', $postIds)->delete();
        Comment::whereIn('post_id', $postIds)->delete();

        // Delete posts in this community
        Post::where('community_id', $communityId)->delete();

        // Delete members in this community
        CommunityMember::where('community_id', $communityId)->delete();

        // Delete the community itself
        $community->delete();

        return response()->json(['message' => 'Community and related records deleted']);
    }

    public function getMembersCountForCommunity($communityId)
{
    $community = Community::findOrFail($communityId);
    $membersCount = $community->memberss()->count();

    return response()->json(['count' => $membersCount]);
}

public function create(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image_url' => 'nullable|string', 
    ]);

    $adminId = auth()->user()->id;

    $community = new Community;
    $community->name = $validatedData['name'];
    $community->description = $validatedData['description'];
    $community->image_url = $validatedData['image_url'];
    $community->admin_id = $adminId;
    $community->visibility = 'public'; 
    $community->save();

    return response()->json(['message' => 'Community created successfully']);
}

public function addAdmin(Community $community, Request $request)
{
    // Assuming you have authenticated the user and obtained their ID
    $userId = auth()->user()->id;

    // Check if the user is already an admin of the community
    if (!$community->admins->contains($userId)) {
        // Attach the user as an admin of the community
        $community->admins()->attach($userId);

        return response()->json(['message' => 'User added as an admin'], 200);
    } else {
        return response()->json(['message' => 'User is already an admin'], 400);
    }
}

}
