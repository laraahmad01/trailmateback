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
use App\Http\Controllers\ProfilePostController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('createuser', [UserController::class, 'create'])->name('createuser');

Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    //Get User Id
    Route::get('/get-user-id', [AuthController::class, 'getUserId']);

    // User
    Route::get('users/{id}', [UserController::class, 'getUser']);
    Route::get('users', [UserController::class, 'getAllUsers']);
    Route::get('userInfo', [UserController::class, 'show']);
    Route::put('userInfo', [UserController::class, 'updateUserInfo']);

    // Collection
    Route::get('/collection', [CollectionController::class, 'index']);
    Route::get('/collection/{id}', [CollectionController::class, 'show']);
    Route::post('/collection', [CollectionController::class, 'store']);
    Route::put('/collection/{id}', [CollectionController::class, 'update']);
    Route::delete('/collection/{id}', [CollectionController::class, 'destroy']);
    Route::get('/collection/{id}/trails', [CollectionController::class, 'getTrailsInCollection']);
    Route::post("/collection/{collectionId}/trails/{trailId}", [CollectionController::class, 'addTrailToCollection']);
    Route::delete('/collection/{collectionId}/trails/{trailId}', [CollectionController::class, 'deleteTrailFromCollection']);

    // Trail Hsitory
    Route::get('/trail-history', [TrailsHistoryController::class, 'index']);
    Route::get('/trail-history/{id}', [TrailsHistoryController::class, 'show']);
    Route::post('/trail-history', [TrailsHistoryController::class, 'store']);
    Route::put('/trail-history/{id}', [TrailsHistoryController::class, 'update']);
    Route::delete('/trail-history/{id}', [TrailsHistoryController::class, 'destroy']);

    // Trails
    Route::get('/trails', [TrailController::class, 'index']);
    Route::get('/trails/{id}', [TrailController::class, 'show']);

    // Community
    Route::post('/communities/create', [CommunityController::class, 'create']);
    Route::get('/communities/user', [CommunityController::class, 'userCommunities']);
    Route::get('/communities', [CommunityController::class, 'index']);
    Route::post('/communities', [CommunityController::class, 'store']);
    Route::get('/communities/{id}', [CommunityController::class, 'show']);
    Route::put('/communities/{id}', [CommunityController::class, 'update']);
    Route::delete('/communities/{id}', [CommunityController::class, 'destroy']);
    Route::get('/communityInfo/{id}', [CommunityController::class, 'showWithMembersAndImages']);
    Route::post('/communities/{community}/admins', [CommunityController::class, 'addAdmin']);

    // Community Member
    Route::get('/communities/{communityId}/members/{memberId}', [CommunityMemberController::class, 'show']);
    Route::put('/communities/{communityId}/members/{memberId}', [CommunityMemberController::class, 'update']);
    Route::delete('/communities/{communityId}/members/{memberId}', [CommunityMemberController::class, 'destroy']);
    Route::get('communities/{communityId}/members', [CommunityMemberController::class, 'getMembersInfo']);
    Route::post('communities/{communityId}/add', [CommunityMemberController::class, 'addMembersToCommunity']);

    // Post 
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts/create', [PostController::class, 'store']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::delete('/posts/delete/{id}', [PostController::class, 'destroy']);
    Route::get('community/{communityId}/postsData', [PostController::class, 'getCommunityPostsData']);
    Route::get('community/postsData', [PostController::class, 'getPublicPostsData']);


    // User Photos
    Route::get('/profile/photos', [ProfilePostController::class, 'showUserPhotos']);
    Route::post('/profile/photos', [ProfilePostController::class, 'storePhoto']);

    // Like
    Route::get('post/{postId}/likes/count', [LikeController::class, 'countLikes']);
    Route::post('/likes/{postId}', [LikeController::class, 'addLike']);
    Route::delete('/likes/{postId}', [LikeController::class, 'deleteLike']);
    Route::get('/likes/{postId}/names', [LikeController::class, 'getLikesNamesForPost']);

    // Comment
    Route::get('post/{postId}/comments/count', [CommentController::class, 'countComments']);
    Route::get('/comments/{postId}', [CommentController::class, 'getCommentsForPost']);
    Route::get('comments/{postId}/data', [CommentController::class, 'getCommentsName']);
    Route::post('comments/{postId}', [CommentController::class, 'addComment']);
    Route::delete('comments/{commentId}', [CommentController::class, 'deleteComment']);

});