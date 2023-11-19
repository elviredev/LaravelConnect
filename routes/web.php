<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// URL homepage pour user authentifié ou non authentifié
Route::get('/', [UserController::class, "showCorrectHomepage"]);

// URL's Authentification
Route::post('/register', [UserController::class, "register"]);
Route::post('/login', [UserController::class, "login"]);
Route::post('/logout', [UserController::class, "logout"]);

// URL's Blog Posts
Route::get('/create-post', [PostController::class, "showCreateForm"]);
Route::post('/create-post', [PostController::class, "storeNewPost"]);
