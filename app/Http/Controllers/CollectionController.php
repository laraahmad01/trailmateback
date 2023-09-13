<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\CollectionTrails;
use App\Models\Trail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $collection = $user->collection;
        
        return response()->json(['collection' => $collection]);
    }

    public function show($id)
    {
        $collection = Collection::findOrFail($id);
        return response()->json(['collection' => $collection]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $collection = new Collection();
        $collection->name = $data['name'];
        $collection->user_id = Auth::id();
        $collection->save();

        return response()->json(['collection' => $collection], 201);
    }

    public function update(Request $request, $id)
    {
        $collection = Collection::findOrFail($id);

        if (!$collection->user->is(Auth::user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $collection->name = $data['name'];
        $collection->save();

        return response()->json(['collection' => $collection]);
    }


    public function destroy($id)
    {
        $collection = Collection::findOrFail($id);

        if (!$collection->user->is(Auth::user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $collection->delete();

        return response()->json(['message' => 'Collection deleted']);
    }

    public function addTrailToCollection($collectionId, $trailId)
    {
        CollectionTrails::create([
            'collection_id' => $collectionId,
            'trail_id' => $trailId,
        ]);

        return response()->json(['message' => 'Trail added to collection successfully']);
    }


    public function getTrailsInCollection($id)
    {
        $collection = Collection::findOrFail($id);
        $trail = $collection->trail;

        return response()->json(['trail' => $trail]);
    }

    public function deleteTrailFromCollection($collectionId, $trailId)
    {
        // Check if the specified trail is in the collection
        $collectionTrail = CollectionTrails::where('collection_id', $collectionId)
            ->where('trail_id', $trailId)
            ->first();

        if (!$collectionTrail) {
            return response()->json(['error' => 'Trail not found in the collection'], 404);
        }

        // Delete the trail from the collection
        $collectionTrail->delete();

        return response()->json(['message' => 'Trail deleted from collection successfully']);
    }
}