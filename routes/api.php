<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExampleController;

Route::get('/get-posts', [ExampleController::class, 'getPosts']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
