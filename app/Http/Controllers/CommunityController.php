<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

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

}
