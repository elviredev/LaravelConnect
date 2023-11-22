<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// URL pour admin only
Route::get('/admins-only', function () {
    return 'Seuls les administrateurs devraient pouvoir voir cette page';
})->middleware('can:visitAdminPages');

// URL homepage pour user authentifié ou non authentifié
Route::get('/', [UserController::class, "showCorrectHomepage"])->name('login');

// URL's Authentification
Route::post('/register', [UserController::class, "register"])->middleware('guest');
Route::post('/login', [UserController::class, "login"])->middleware('guest');
Route::post('/logout', [UserController::class, "logout"])->middleware('mustBeLoggedIn');

// URL's Blog Posts
Route::get('/create-post', [PostController::class, "showCreateForm"])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, "createNewPost"])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, "showSinglePost"]);
Route::delete('/post/{post}', [PostController::class, "delete"])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, "showEditForm"])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'updatePost'])->middleware('can:update,post');

// URL's Profil utilisateur
Route::get('/profile/{user:username}', [UserController::class, 'profile']);
