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
use App\Http\Controllers\TrailsHistoryController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ListOfCollectionController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('createuser',[UserController::class,'create'])->name('create');
Route::post('createuser',[UserController::class,'create'])->name('createuser');
Route::get('users/{id}', [UserController::class, 'getUser']);
Route::get('users', [UserController::class, 'getAllUsers']);


Route::post('/communities/create', [CommunityController::class, 'create']);
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
Route::post('/communities/{community}/admins', [CommunityController::class, 'addAdmin']);


Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::put('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);
Route::get('community/{communityId}/posts', [PostController::class, 'getCommunityPosts']);
Route::get('post/{postId}/comments/count', [PostController::class, 'getCommentsCountForPost']);
Route::get('post/{postId}/likes/count', [PostController::class, 'getLikesCountForPost']);


Route::get('/likes/{postId}', [LikeController::class, 'getLikesForPost']);
Route::post('/likes/{postId}', [LikeController::class, 'addLikeToPost']);
Route::delete('/likes/{postId}/{likeId}', [LikeController::class, 'removeLikeFromPost']);
Route::get('post/{postId}/likes/names', [LikeController::class, 'getLikesNames']);

Route::get('/comments/{postId}', [CommentController::class, 'getCommentsForPost']);
Route::get('comments/{postId}/data', [CommentController::class, 'getCommentsName']);
Route::post('comments/{postId}', [CommentController::class, 'addComment']);
Route::delete('comments/{commentId}', [CommentController::class, 'deleteComment']);

Route::get('/collections', [CollectionController::class, 'index']);
Route::get('/collections/{id}', [CollectionController::class, 'show']);
Route::post('/collections', [CollectionController::class, 'store']);
Route::put('/collections/{id}', [CollectionController::class, 'update']);
Route::delete('/collections/{id}', [CollectionController::class, 'destroy']);

Route::get('/trail-history', [TrailsHistoryController::class, 'index']);
Route::get('/trail-history/{id}', [TrailsHistoryController::class, 'show']);
Route::post('/trail-history', [TrailsHistoryController::class, 'store']);
Route::put('/trail-history/{id}', [TrailsHistoryController::class, 'update']);
Route::delete('/trail-history/{id}', [TrailsHistoryController::class, 'destroy']);

