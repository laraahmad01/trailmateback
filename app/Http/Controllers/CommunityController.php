<?php

namespace App\Http\Controllers;

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
        try {
            $community = Community::findOrFail($id);

            $community->name = $request->input('name');
            $community->description = $request->input('description');
            $community->save();

            return response()->json(['message' => 'Community updated successfully', 'community' => $community]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating post', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $community = Community::findOrFail($id);
        $community->delete();
        return response()->json(null, 204);
    }

    public function delete($id)
    {
        try {
            $community = Community::findOrFail($id);

            // Delete related records (likes and comments)
            foreach ($community->posts as $post) {
                $post->likes()->delete();
                $post->comments()->delete();
            }

            // Delete posts
            $community->posts()->delete();

            // Delete members
            $community->members()->delete();

            // Delete the community itself
            $community->delete();

            return response()->json(['message' => 'Community and related records deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting community', 'error' => $e->getMessage()], 500);
        }
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
            'visibility' => 'nullable|string|in:public,private',
        ]);

        $adminId = auth()->user()->id;

        $community = new Community;
        $community->name = $validatedData['name'];
        $community->description = $validatedData['description'];
        $community->image_url = $validatedData['image_url'] ?? null; 
        $community->admin_id = $adminId;
        $community->visibility = $validatedData['visibility'] ?? 'public'; 
        $community->save();

        $communityMember = new CommunityMember;
        $communityMember->community_id = $community->id;
        $communityMember->member_id = $adminId;
        $communityMember->is_admin = 1; 
        $communityMember->muted = 0;
        $communityMember->save();

        return response()->json(['message' => 'Community created successfully', 'community' => $community]);
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


}