<?php

namespace App\Http\Controllers;

use App\Models\CommunityMember;
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

    
}
