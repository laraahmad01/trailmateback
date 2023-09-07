<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TrailsHistory;
use Illuminate\Http\Request;

class TrailsHistoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $trailHistory = $user->TrailsHistory()->paginate(10); // Adjust the pagination as needed

        return response()->json(['trail_history' => $trailHistory]);
    }

    public function show($id)
    {
        $trailHistory = TrailsHistory::findOrFail($id);

        // Check if the history entry belongs to the authenticated user
        if (!$trailHistory->user->is(auth()->user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['trail_history' => $trailHistory]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'trail_id' => 'required|exists:trails,id',
            'hike_date' => 'required|date',
        ]);

        $trailHistory = new TrailsHistory();
        $trailHistory->user_id = auth()->id();
        $trailHistory->trail_id = $data['trail_id'];
        $trailHistory->hike_date = $data['hike_date'];
        $trailHistory->save();

        return response()->json(['trail_history' => $trailHistory], 201);
    }

    public function update(Request $request, $id)
    {
        $trailHistory = TrailsHistory::findOrFail($id);

        // Check if the history entry belongs to the authenticated user
        if (!$trailHistory->user->is(auth()->user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'hike_date' => 'required|date',
        ]);

        $trailHistory->hike_date = $data['hike_date'];
        $trailHistory->save();

        return response()->json(['trail_history' => $trailHistory]);
    }

    public function destroy($id)
    {
        $trailHistory = TrailsHistory::findOrFail($id);

        // Check if the history entry belongs to the authenticated user
        if (!$trailHistory->user->is(auth()->user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $trailHistory->delete();

        return response()->json(['message' => 'Trail history entry deleted']);
    }

}