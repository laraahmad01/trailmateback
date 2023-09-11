<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CommunityMemberController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TrailsHistoryController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\TrailController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

// Route::post('createuser',[UserController::class,'create'])->name('createuser');
// Route::get('users/{id}', [UserController::class, 'getUser']);
// Route::get('users', [UserController::class, 'getAllUsers']);

// Route::post('/communities/create', [CommunityController::class, 'create']);
// Route::get('/communities', [CommunityController::class, 'index']);
// Route::post('/communities', [CommunityController::class, 'store']);
// Route::get('/communities/{id}', [CommunityController::class, 'show']);
// Route::put('/communities/{id}', [CommunityController::class, 'update']);
// Route::delete('/communities/{id}', [CommunityController::class, 'destroy']);
// Route::delete('communities/{communityId}', [CommunityController::class, 'deleteCommunity']);
// Route::get('community/{communityId}/members/count', [CommunityController::class, 'getMembersCountForCommunity']);
Route::post('/communities/create', [CommunityController::class, 'create']);
Route::get('/communities', [CommunityController::class, 'index']);
Route::post('/communities', [CommunityController::class, 'store']);
Route::get('/communities/{id}', [CommunityController::class, 'show']);
Route::post('/communities/update/{id}', [CommunityController::class, 'update']);
Route::delete('/communities/delete/{id}', [CommunityController::class, 'destroy']);
Route::get('community/{communityId}/members/count', [CommunityController::class, 'getMembersCountForCommunity']);
Route::post('/communities/{community}/admins', [CommunityController::class, 'addAdmin']);

Route::get('/communities/{communityId}/members', [CommunityMemberController::class, 'index']);
Route::post('/communities/{communityId}/members', [CommunityMemberController::class, 'store']);
Route::get('/communities/{communityId}/members/{memberId}', [CommunityMemberController::class, 'show']);
Route::put('/communities/{communityId}/members/{memberId}', [CommunityMemberController::class, 'update']);
Route::delete('/communities/{communityId}/members/{memberId}', [CommunityMemberController::class, 'destroy']);
Route::get('community/{communityId}/members/names', [CommunityMemberController::class, 'getMembersNames']);
Route::post('/communitymembers/add', [CommunityMemberController::class, 'addMembersToCommunity']);
Route::get('/communitymembers/{communityId}', [CommunityMemberController::class, 'getCommunityMembers']);

// Route::get('/communities/{communityId}/members', [CommunityMemberController::class, 'index']);
// Route::post('/communities/{communityId}/members', [CommunityMemberController::class, 'store']);
// Route::get('/communities/{communityId}/members/{memberId}', [CommunityMemberController::class, 'show']);
// Route::put('/communities/{communityId}/members/{memberId}', [CommunityMemberController::class, 'update']);
// Route::delete('/communities/{communityId}/members/{memberId}', [CommunityMemberController::class, 'destroy']);
// Route::get('community/{communityId}/members/names', [CommunityMemberController::class, 'getMembersNames']);
// Route::post('/communities/{community}/admins', [CommunityController::class, 'addAdmin']);

// Route::get('/posts', [PostController::class, 'index']);
// Route::post('/posts', [PostController::class, 'store']);
// Route::get('/posts/{id}', [PostController::class, 'show']);
// Route::put('/posts/{id}', [PostController::class, 'update']);
// Route::delete('/posts/{id}', [PostController::class, 'destroy']);
// Route::get('community/{communityId}/posts', [PostController::class, 'getCommunityPosts']);
// Route::get('post/{postId}/comments/count', [PostController::class, 'getCommentsCountForPost']);
// Route::get('post/{postId}/likes/count', [PostController::class, 'getLikesCountForPost']);
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts/create', [PostController::class, 'store']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::post('/posts/update/{id}', [PostController::class, 'update']);
Route::delete('/posts/delete/{id}', [PostController::class, 'destroy']);
Route::get('community/{communityId}/posts', [PostController::class, 'getCommunityPosts']);
Route::get('post/{postId}/comments/count', [PostController::class, 'getCommentsCountForPost']);
Route::get('post/{postId}/likes/count', [PostController::class, 'getLikesCountForPost']);

// Route::get('/likes/{postId}', [LikeController::class, 'getLikesForPost']);
// Route::post('/likes/{postId}', [LikeController::class, 'addLikeToPost']);
// Route::delete('/likes/{postId}/{likeId}', [LikeController::class, 'removeLikeFromPost']);
// Route::get('post/{postId}/likes/names', [LikeController::class, 'getLikesNames']);

// Route::get('/comments/{postId}', [CommentController::class, 'getCommentsForPost']);
// Route::get('comments/{postId}/data', [CommentController::class, 'getCommentsName']);
// Route::post('comments/{postId}', [CommentController::class, 'addComment']);
// Route::delete('comments/{commentId}', [CommentController::class, 'deleteComment']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/collection', [CollectionController::class, 'index']);
    Route::get('/collection/{id}', [CollectionController::class, 'show']);
    Route::post('/collection', [CollectionController::class, 'store']);
    Route::put('/collection/{id}', [CollectionController::class, 'update']);
    Route::delete('/collection/{id}', [CollectionController::class, 'destroy']);

    Route::get('/trail-history', [TrailsHistoryController::class, 'index']);
    Route::get('/trail-history/{id}', [TrailsHistoryController::class, 'show']);
    Route::post('/trail-history', [TrailsHistoryController::class, 'store']);
    Route::put('/trail-history/{id}', [TrailsHistoryController::class, 'update']);
    Route::delete('/trail-history/{id}', [TrailsHistoryController::class, 'destroy']);

    Route::get('/trails', [TrailController::class, 'index']);
    Route::get('/trails/{id}', [TrailController::class, 'show']);

    Route::get('/collection/{id}/trails', [CollectionController::class, 'getCollectionTrails']);

});