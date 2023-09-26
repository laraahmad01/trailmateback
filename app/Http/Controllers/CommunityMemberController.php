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

    public function addMembersToCommunity(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $communityId = $request->input('communityId');
        $selectedMembers = $request->input('selectedMembers');

        // Exclude the authenticated user's ID from the selected members
        $selectedMembers = array_diff($selectedMembers, [$user->id]);

        $existingUserIds = User::whereIn('id', $selectedMembers)->pluck('id')->toArray();

        if (count($existingUserIds) !== count($selectedMembers)) {
            return response()->json(['message' => 'Invalid user ID(s) provided'], 400);
        }

        foreach ($selectedMembers as $userId) {
            CommunityMember::create([
                'community_id' => $communityId,
                'member_id' => $userId,
                'is_admin' => false,
                'muted' => false,
            ]);
        }

        return response()->json(['message' => 'Members added successfully']);
    }


    public function getMembersInfo($communityId)
    {
        try {
            $membersNames = DB::table('community_members')
                ->join('users', 'users.id', '=', 'community_members.member_id')
                ->where('community_members.community_id', $communityId)
                ->select('users.firstname', 'users.lastname')
                ->get();

            return response()->json($membersNames);
        } catch (\Exception $e) {
            Log::error('Error fetching members names:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching member names.'], 500);
        }
    }
}