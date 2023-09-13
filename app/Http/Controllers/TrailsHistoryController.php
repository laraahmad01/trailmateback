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
        $trailsHistory = TrailsHistory::where('user_id', $user->id)
            ->with('trail')
            ->get();

        return response()->json(['trails_history' => $trailsHistory]);
    }

    public function show($id)
    {
        $trailsHistory = TrailsHistory::findOrFail($id);

        // Check if the history entry belongs to the authenticated user
        if (!$trailsHistory->user->is(auth()->user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['trails_history' => $trailsHistory]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'trail_id' => 'required|exists:trails,id',
            'hike_date' => 'required|date',
        ]);

        $trailsHistory = new TrailsHistory();
        $trailsHistory->user_id = auth()->id();
        $trailsHistory->trail_id = $data['trail_id'];
        $trailsHistory->hike_date = $data['hike_date'];
        $trailsHistory->save();

        return response()->json(['trails_history' => $trailsHistory], 201);
    }

    public function update(Request $request, $id)
    {
        $trailsHistory = TrailsHistory::findOrFail($id);

        // Check if the history entry belongs to the authenticated user
        if (!$trailsHistory->user->is(auth()->user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'hike_date' => 'required|date',
        ]);

        $trailsHistory->hike_date = $data['hike_date'];
        $trailsHistory->save();

        return response()->json(['trails_history' => $trailsHistory]);
    }

    public function destroy($id)
    {
        $trailsHistory = TrailsHistory::findOrFail($id);

        // Check if the history entry belongs to the authenticated user
        if (!$trailsHistory->user->is(auth()->user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $trailsHistory->delete();

        return response()->json(['message' => 'Trail history entry deleted']);
    }

}