<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Affichage du formulaire de création d'un article
    public function showCreateForm() {
        return view('create-post');
    }
    // Créer un article
    public function storeNewPost(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        // nettoyer les tags html et récupérer l'id de l'user dans la session en cours
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        // sauveagarde en bdd
        Post::create($incomingFields);

        return "Hey !!!";
    }
}
