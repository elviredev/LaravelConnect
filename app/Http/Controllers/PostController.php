<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewPostEmail;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /* Affichage du formulaire de création d'un article */
    public function showCreateForm() {
        return view('create-post');
    }

    /* Créer un article */
    public function createNewPost(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => ['required', 'min:20', 'max:750']
        ]);
        // nettoyer les tags html et récupérer l'id de l'user dans la session en cours
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        // sauvegarde en bdd
        $newPost = Post::create($incomingFields);

        // exécuter la tâche d'envoi d'un email via notre job SendNewpostEmail
        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'name' => auth()->user()->username, 'title' => $newPost->title]));

        return redirect("/post/$newPost->id")->with('success', 'Nouvel article créé 👏🏼');
    }

    /* Créer un article via notre API */
    public function createNewPostApi(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => ['required', 'min:20', 'max:750']
        ]);
        // nettoyer les tags html et récupérer l'id de l'user dans la session en cours
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        // sauvegarde en bdd
        $newPost = Post::create($incomingFields);

        // exécuter la tâche d'envoi d'un email via notre job SendNewpostEmail
        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'name' => auth()->user()->username, 'title' => $newPost->title]));

        return $newPost->id;
    }

    /* Voir un article */
    public function showSinglePost(Post $post) {
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><h1><h2><h3><h4><br><blockquote><code><pre>');
        return view('single-post', ['post' => $post]);
    }

    /* Supprimer un article */
    public function delete(Post $post) {
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'L\'article a bien été supprimé.');
    }

    /* Supprimer un article depuis l'API */
    public function deleteApi(Post $post) {
        $post->delete();
        return 'true';
    }

    /* Voir le formulaire de modification d'un article */
    public function showEditForm(Post $post) {
        return view('edit-post', ['post' => $post]);
    }

    /* Modifier un article */
    public function updatePost(Post $post, Request $request) {
        // Validation des champs entrants
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => ['required', 'min:20', 'max:750']
        ]);

        // nettoyer les tags html
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        // sauvegarder les modifications en bdd
        $post->update($incomingFields);

        return back()->with('success', 'L\'article a bien été modifié 👍🏼 !');
    }

    /* Rechercher un article par un mot-clé */
    public function search($term) {
        $posts = Post::search($term)->get();
        // Récupérer données de la table "users":id, username et avatar
        $posts->load('user:id,username,avatar');
        return $posts;
    }
}
