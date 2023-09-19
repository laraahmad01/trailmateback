<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function getCommentsForPost($postId)
    {
        try {
            $comments = Comment::where('post_id', $postId)->get();
            return response()->json($comments);
        } catch (\Exception $e) {
            Log::error('Error fetching community posts:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching community posts.'], 500);
        }
    }

    public function getPostComments($postId)
    {
        try {
            $comments = Comment::where('post_id', $postId)->get();
            return response()->json($comments);
        } catch (\Exception $e) {
            Log::error('Error fetching post comments:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching post comments.'], 500);
        }
    }

    public function deleteComment($commentId)
    {
        try {
            $comment = Comment::find($commentId);

            if (!$comment) {
                return response()->json(['message' => 'Comment not found'], 404);
            }

            $comment->delete();

            return response()->json(['message' => 'Comment deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting comment:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while deleting a comment.'], 500);
        }
    }

    public function addComment(Request $request)
    {
        try {
            $userId = $request->user_id;

            $comment = new Comment;
            $comment->user_id = $userId;
            $comment->post_id = $request->input('post_id');
            $comment->content = $request->input('content');
            date_default_timezone_set('Asia/Beirut');
            $comment->created_at = date('Y-m-d H:i:s');

            $comment->save();

            return response()->json(['message' => 'Comment added successfully', 'comment' => $comment]);
        } catch (\Exception $e) {
            Log::error('Error adding comment:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while adding a comment.'], 500);
        }
    }

    public function countComments($postId)
    {
        $commentCount = Comment::where('post_id', $postId)->count();
        return response()->json(['count' => $commentCount]);
    }
}