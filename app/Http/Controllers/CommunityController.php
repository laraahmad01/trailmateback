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
        // Validate the incoming request data (you can adjust validation rules)
       // $request->validate([
         //   'description' => 'required|string|max:255', // Adjust validation rules as needed
        //]);
    
        try {
            $community = Community::findOrFail($id);
    
            // You can update only the fields that need to be updated
            $community->name = $request->input('name');
            $community->description = $request->input('description');
            // Save the updated post
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
        'admin_id' => 'nullable|integer', // Use 'integer' instead of 'number'
        'visibility' => 'nullable|string',
    ]);


    // Create the community
    $community = new Community;
    $community->name = $request->input('name');
    $community->description = $request->input('description');
    $community->image_url = '12345';
    $community->admin_id = Auth::id();
    $community->visibility = 'public'; 
    $community->save();

    // Create the community member record for the admin
    $communityMember = new CommunityMember;
    $communityMember->community_id = $community->id;
    $communityMember->member_id = $community->admin_id;
    $communityMember->is_admin = 1; // Admin status
    $communityMember->muted = 0;    // Not muted
    $communityMember->save();

    return response()->json(['message' => 'Community created successfully', 'community' => $community]);
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
