<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class ExampleController extends Controller
{
    public function getPosts()
    {
        $posts = Post::all();
        return response()->json($posts);
    }
}
