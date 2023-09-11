<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Import Log
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function getCommentsForPost($postId)
    {
        try{
        $comments = Comment::where('post_id', $postId)->get();

        return response()->json($comments);}
        catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error fetching community posts:', ['error' => $e->getMessage()]);
    
            // Return an error response
            return response()->json(['error' => 'An error occurred while fetching community posts.'], 500);
        }
    }
    
    public function getCommentsName($postId)
{
    try {
        $commentsNames = DB::table('comments')
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->where('comments.post_id', $postId)
            ->select('users.firstname', 'users.lastname', 'comments.content', 'comments.created_at')
            ->get();

        if ($commentsNames->isEmpty()) {
            return response()->json(['message' => 'No comments found for this post.']);
        }

        return response()->json($commentsNames);
    } 
    catch (\Exception $e) {
        Log::error('Error fetching comments:', ['error' => $e->getMessage()]);

        return response()->json(['error' => 'An error occurred while fetching comments.'], 500);
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
    } 
    catch (\Exception $e) {
        // Log the error for debugging purposes
        Log::error('Error deleting comment:', ['error' => $e->getMessage()]);

        // Return an error response
        return response()->json(['error' => 'An error occurred while deleting a comment.'], 500);
    }
}

    public function addComment(Request $request, $postId)
{
    try {
        // Assuming you pass user_id in the request, you can get it as $userId
        $userId = $request->user_id;
        
        $comment = new Comment();
        $comment->user_id = 1;
        $comment->post_id = $postId;
        $comment->content = $request->input('content'); // Assuming you send the comment content in the request
        
        $comment->save();

        return response()->json(['message' => 'Comment added successfully']);
    } 
    catch (\Exception $e) {
        // Log the error for debugging purposes
        Log::error('Error adding comment:', ['error' => $e->getMessage()]);

        // Return an error response
        return response()->json(['error' => 'An error occurred while adding a comment.'], 500);
    }
}
}
