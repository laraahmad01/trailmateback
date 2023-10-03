<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function countComments($postId)
    {
        $commentCount = Comment::where('post_id', $postId)->count();
        return response()->json(['count' => $commentCount]);
    }

    public function getCommentsWithUserInfo($postId)
    {
        $comments = Comment::where('post_id', $postId)
            ->with(['user:id,firstname,lastname,image_url'])
            ->get();

        $authUser = Auth::user();

        return response()->json(['comments' => $comments, 'authUser' => $authUser]);
    }

    public function store(Request $request, $postId)
    {
        $validatedData = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->post_id = $postId;
        $comment->content = $validatedData['content'];
        $comment->save();

        return response()->json(['message' => 'Comment created successfully']);
    }

    public function destroy($postId, $commentId)
    {
        $comment = Comment::where('post_id', $postId)->findOrFail($commentId);

        if (Auth::id() === $comment->user_id) {
            $comment->delete();
            return response()->json(['message' => 'Comment deleted successfully']);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }


}