<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Trail;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(){
        $user = auth()->user();
        $collections = $user->collections;

        return response()->json(['collections' => $collections]);
    }

    public function show($id)
    {
        $collection = Collection::findOrFail($id);

        // if (!$collection->user->is(auth()->user())) {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }

        return response()->json(['collection' => $collection]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $collection = new Collection();
        $collection->name = $data['name'];
        $collection->user_id = auth()->id();
        $collection->save();

        return response()->json(['collection' => $collection], 201);
    }

    public function update(Request $request, $id)
    {
        $collection = Collection::findOrFail($id);

        if (!$collection->user->is(auth()->user())) {
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

        if (!$collection->user->is(auth()->user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $collection->delete();

        return response()->json(['message' => 'Collection deleted']);
    }

    public function addTrailToCollection($collectionId, $trailId)
    {
        $collection = Collection::findOrFail($collectionId);
        $trail = Trail::findOrFail($trailId);

        $collection->trails()->attach($trail->id);

        // You can also use detach to remove a trail from a collection:
        // $collection->trails()->detach($trail->id);

        return response()->json(['message' => 'Trail added to collection successfully']);
    }
}