<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityMember;
use App\Models\User;
use Auth;
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
        try {
            $user = Auth::user();

            // Check if the user is an admin of the specified community
            $isAdmin = CommunityMember::where('community_id', $communityId)
                ->where('member_id', $user->id)
                ->where('is_admin', true)
                ->exists();

            if (!$isAdmin) {
                return response()->json(['message' => 'User is not an admin of the community'], 403);
            }

            // Check if the member being removed is an admin
            $isMemberAdmin = CommunityMember::where('community_id', $communityId)
                ->where('member_id', $memberId)
                ->where('is_admin', true)
                ->exists();

            if ($isMemberAdmin) {
                return response()->json(['message' => 'Cannot remove an admin member from the community'], 400);
            }

            // If the user is an admin and the member is not an admin, proceed with removing the member
            $member = CommunityMember::where('community_id', $communityId)
                ->findOrFail($memberId);

            $member->delete();

            return response()->json(['message' => 'Member deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting community member', 'error' => $e->getMessage()], 500);
        }
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
            $authenticatedUserId = Auth::user()->id;

            // Get the admin_id of the community
            $adminId = Community::findOrFail($communityId)->admin_id;

            $membersInfo = DB::table('community_members')
                ->join('users', 'users.id', '=', 'community_members.member_id')
                ->where('community_members.community_id', $communityId)
                ->select(
                    'community_members.id',
                    'users.id as userId',
                    'users.firstname',
                    'users.lastname',
                    'users.image_url',
                    'community_members.is_admin',
                    'community_members.muted'
                )
                ->get();

            $response = [
                'authenticatedUserId' => $authenticatedUserId,
                'admin_id' => $adminId,
                // Include admin_id in the response
                'membersInfo' => $membersInfo,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error fetching members info:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching member info.'], 500);
        }
    }



}