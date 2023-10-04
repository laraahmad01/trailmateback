<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Trail;
use Illuminate\Http\Request;

class TrailController extends Controller
{
    public function index()
    {
        $trail = Trail::all();
        return response()->json(['trail' => $trail]);
    }

    public function show($id)
    {
        $trail = Trail::findOrFail($id);
        return response()->json(['trail' => $trail]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query'); 

        $results = Trail::where('name', 'LIKE', "%$query%")
            ->orWhere('location', 'LIKE', "%$query%")
            ->get();

        return response()->json(['results' => $results]);
    }

}
