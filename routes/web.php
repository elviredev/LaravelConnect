<?php

use App\Events\ChatMessage;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

// URL's avatar
Route::get('/manage-avatar', [UserController::class, "showAvatarForm"])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar', [UserController::class, "storeAvatar"])->middleware('mustBeLoggedIn');

// URL's Follows
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow'])->middleware('mustBeLoggedIn');

// URL's Blog Posts
Route::get('/create-post', [PostController::class, "showCreateForm"])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, "createNewPost"])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, "showSinglePost"]);
Route::delete('/post/{post}', [PostController::class, "delete"])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, "showEditForm"])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'updatePost'])->middleware('can:update,post');
Route::get('/search/{term}', [PostController::class, 'search']);

// URL's Profil utilisateur
Route::get('/profile/{user:username}', [UserController::class, 'profile']);
Route::get('/profile/{user:username}/followers', [UserController::class, 'profileFollowers']);
Route::get('/profile/{user:username}/following', [UserController::class, 'profileFollowing']);
// Routes pour Profile SPA (javascript côté client)
Route::middleware('cache.headers:public;max_age=20;etag')->group(function() {
    Route::get('/profile/{user:username}/raw', [UserController::class, 'profileRaw']);
    Route::get('/profile/{user:username}/followers/raw', [UserController::class, 'profileFollowersRaw']);
    Route::get('/profile/{user:username}/following/raw', [UserController::class, 'profileFollowingRaw']);
});

// URL pour le tchat - Pas de contrôleur
Route::post('/send-chat-message', function (Request $request) {
    $formFields = $request->validate([
        'textValue' => 'required'
    ]);
    // si texte non valide : espaces vides avant et après textValue ou caractères non aurorisés
    if (!trim(strip_tags($formFields['textValue']))) {
        return response()->noContent();
    }
    // diffuser le message
    broadcast(new ChatMessage(['username' => auth()->user()->username, 'textValue' => strip_tags($request->textValue), 'avatar' => auth()->user()->avatar]))->toOthers();

    // envoyer la réponse sans contenu (sans code 200 ni msg de succès)
    return response()->noContent();
})->middleware('mustBeLoggedIn');
