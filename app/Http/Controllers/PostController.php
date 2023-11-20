<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // Affichage du formulaire de création d'un article
    public function showCreateForm() {
        return view('create-post');
    }

    // Créer un article
    public function createNewPost(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        // nettoyer les tags html et récupérer l'id de l'user dans la session en cours
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        // sauveagarde en bdd
        $newPost = Post::create($incomingFields);

        return redirect("/post/$newPost->id")->with('success', 'Nouvel article créé 👏🏼');
    }

    // Voir un article
    public function showSinglePost(Post $post) {
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><h3><br>');
        return view('single-post', ['post' => $post]);
    }
}
