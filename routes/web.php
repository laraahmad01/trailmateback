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
use App\Http\Controllers\TrailController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});
