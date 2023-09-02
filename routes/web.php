<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CommunityMemberController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('welcome');
});
Route::get('createuser',[UserController::class,'create'])->name('create');
Route::post('createuser',[UserController::class,'create'])->name('createuser');
Route::get('users/{id}', [UserController::class, 'getUser']);
Route::get('users', [UserController::class, 'getAllUsers']);


Route::get('/communities', [CommunityController::class, 'index']);
Route::post('/communities', [CommunityController::class, 'store']);
Route::get('/communities/{id}', [CommunityController::class, 'show']);
Route::put('/communities/{id}', [CommunityController::class, 'update']);
Route::delete('/communities/{id}', [CommunityController::class, 'destroy']);
Route::delete('communities/{communityId}', [CommunityController::class, 'deleteCommunity']);
Route::get('community/{communityId}/members/count', [CommunityController::class, 'getMembersCountForCommunity']);

Route::get('/communities/{communityId}/members', [CommunityMemberController::class, 'index']);
Route::post('/communities/{communityId}/members', [CommunityMemberController::class, 'store']);
Route::get('/communities/{communityId}/members/{memberId}', [CommunityMemberController::class, 'show']);
Route::put('/communities/{communityId}/members/{memberId}', [CommunityMemberController::class, 'update']);
Route::delete('/communities/{communityId}/members/{memberId}', [CommunityMemberController::class, 'destroy']);
Route::get('community/{communityId}/members/names', [CommunityMemberController::class, 'getMembersNames']);

Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::put('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);
Route::get('community/{communityId}/posts', [PostController::class, 'getCommunityPosts']);

Route::get('/likes/{postId}', [LikeController::class, 'getLikesForPost']);
Route::post('/likes/{postId}', [LikeController::class, 'addLikeToPost']);
Route::delete('/likes/{postId}/{likeId}', [LikeController::class, 'removeLikeFromPost']);
Route::get('post/{postId}/likes/names', [LikeController::class, 'getLikesNames']);

Route::get('/comments/{postId}', [CommentController::class, 'getCommentsForPost']);
Route::get('comments/{postId}/data', [CommentController::class, 'getCommentsName']);
Route::post('comments/{postId}', [CommentController::class, 'addComment']);
Route::delete('comments/{commentId}', [CommentController::class, 'deleteComment']);
