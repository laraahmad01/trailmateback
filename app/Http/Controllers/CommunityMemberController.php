<?php

namespace App\Http\Controllers;

use App\Models\CommunityMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class CommunityMemberController extends Controller
{
    public function index($communityId)
    {
        $communityMembers = CommunityMember::with('user')
            ->where('community_id', $communityId)
            ->get();

        return response()->json($communityMembers);
    }

    public function addMembersToCommunity(Request $request)
    {
        // Check if the user is authenticated
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $communityId = $request->input('communityId');
        $selectedMembers = $request->input('selectedMembers');

        // Validate that all selectedMembers exist
        $existingUserIds = User::whereIn('id', $selectedMembers)->pluck('id')->toArray();

        if (count($existingUserIds) !== count($selectedMembers)) {
            return response()->json(['message' => 'Invalid user ID(s) provided'], 400);
        }

        foreach ($selectedMembers as $userId) {
            // Create community members
            CommunityMember::create([
                'community_id' => $communityId,
                'member_id' => $userId,
                // Ensure 'member_id' is used here
                'is_admin' => false,
                'muted' => false,
            ]);
        }

        return response()->json(['message' => 'Members added successfully']);
    }




    public function getMembersNames($communityId)
    {
        try {
            $membersNames = DB::table('community_members')
                ->join('users', 'users.id', '=', 'community_members.member_id')
                ->where('community_members.community_id', $communityId)
                ->select('users.firstname', 'users.lastname')
                ->get();

            return response()->json($membersNames);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error fetching members names:', ['error' => $e->getMessage()]);

            // Return an error response
            return response()->json(['error' => 'An error occurred while fetching member names.'], 500);
        }
    }


    public function getCommunityMembers($communityId)
    {
        $members = CommunityMember::where('community_id', $communityId)->get();

        return response()->json(['members' => $members]);
    }

    public function store(Request $request, $communityId)
    {
        $data = $request->all();
        $data['community_id'] = $communityId;

        $member = CommunityMember::create($data);
        return response()->json($member, 201);
    }

    public function show($communityId, $memberId)
    {
        $member = CommunityMember::where('community_id', $communityId)
            ->findOrFail($memberId);
        return response()->json($member);
    }

    public function update(Request $request, $communityId, $memberId)
    {
        $member = CommunityMember::where('community_id', $communityId)
            ->findOrFail($memberId);
        $member->update($request->all());
        return response()->json($member, 200);
    }

    public function destroy($communityId, $memberId)
    {
        $member = CommunityMember::where('community_id', $communityId)
            ->findOrFail($memberId);
        $member->delete();
        return response()->json(null, 204);
    }
    //public function destroy($id)
//{
    // Get the currently authenticated user
    //$user = Auth::user();
    //  $user = User::find(1); // Replace with your logic to get the authenticated user
    //if (!$user) {
    //  return response()->json(['message' => 'User not authenticated'], 401);
    //}

    // Find the member with the given ID
    //$member = Member::find($id);

    //if (!$member) {
    //  return response()->json(['message' => 'Member not found'], 404);
    //}

    // Check if the user is the member being deleted or has admin role in the community
    //if ($member->user_id === $user->id || $user->isAdminInCommunity($member->community_id)) {
    // Check if the user is deleting themselves from the community
    //  if ($member->user_id === $user->id && $member->community_id === $user->community_id) {
    // User is deleting themselves, proceed with deletion
    //    try {
    // Delete the member
    //      $member->delete();

    //    return response()->json(['message' => 'Member deleted successfully']);
    //} catch (\Exception $e) {
    //  return response()->json(['message' => 'Error deleting member', 'error' => $e->getMessage()], 500);
    //}
    //} else {
    // User is not authorized to delete another member
    //  return response()->json(['message' => 'User is not authorized to delete another member'], 403);
    //}
    //} else {
    // User is not authorized to delete the member
    //  return response()->json(['message' => 'User is not authorized to delete member'], 403);
    //}
//}


}