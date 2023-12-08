<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
 * Authentification API
 * URL's pour se connecter, créer/supprimer un article
 */

Route::post('/login', [UserController::class, 'loginApi']);
Route::post('/create-post', [PostController::class, 'createNewPostApi'])->middleware('auth:sanctum');
Route::delete('/delete-post/{post}', [PostController::class, 'deleteApi'])->middleware('auth:sanctum', 'can:delete,post');
