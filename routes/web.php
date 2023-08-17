<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('createuser',[UserController::class,'create'])->name('create');
Route::post('createuser',[UserController::class,'create'])->name('createuser');
Route::get('users/{id}', [UserController::class, 'getUser']);
Route::get('users', [UserController::class, 'getAllUsers']);

Route::post('/posts', [PostController::class, 'create']);
