<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Collection;
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

    public function addTrailToCollection($id, $trailId)
    {
        $collection = Collection::findOrFail($id);
        $trail = Trail::findOrFail($trailId);

        $collection->trails()->attach($trail->id);

        return response()->json(['message' => 'Trail added to collection successfully']);
    }

    public function getCollectionTrails($id)
    {
        $collection = Collection::findOrFail($id);
        $trail = $collection->trail;

        return response()->json(['trail' => $trail]);
    }
}