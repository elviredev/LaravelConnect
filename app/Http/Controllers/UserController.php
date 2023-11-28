<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /* Créer un compte utilisateur */
    public function  register(Request $request) {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:6', 'confirmed']
        ]);
        // créé le compte en BDD si validation OK
        $user = User::create($incomingFields);
        // connecter le user qui vient d'être créé
        auth()->login($user);
        return redirect('/')->with('success', 'Merci d\'avoir créé un compte 🤗');
    }

    /* Se connecter en tant qu'utilisateur */
    public function login(Request $request) {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            // si la tentative réussit on connecte le user
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Vous êtes maintenant connecté 🦄');
        } else {
            return redirect('/')->with('failure', 'Connexion invalide ❌. Veuillez réessayer.');
        }
    }

    /* Se déconnecter */
    public function logout() {
        auth()->logout();
        return redirect('/')->with('success', 'Vous êtes maintenant déconnecté 🦉');
    }

    /*
     * Modèle de vue à afficher selon que le user est connecté ou non
     */
    public function showCorrectHomepage() {
        // si user authentifié
        if (auth()->check()) {
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->get()]);
        } else {
            // si pas authentifié
            return view('homepage');
        }
    }

    /*
     * Fonction commune aux 3 controllers profile, profileFollowers
     * et profileFollowing
     * */
    private function getSharedData($user) {
        $currentlyFollowing = 0;

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }

        View::share("sharedData", [
            'currentlyFollowing' => $currentlyFollowing,
            'username' => $user->username,
            'postCount' => $user->posts()->count(),
            'followerCount' => $user->followers()->count(),
            'followingCount' => $user->followingTheseUsers()->count(),
            'avatar' => $user->avatar
        ]);
    }

    /* Afficher la page du profil utilisateur et la liste de ses articles */
    public function profile(User $user) {
        $this->getSharedData($user);
        return view('profile-posts', ['posts' => $user->posts()->latest()->get()]);
    }

    /* Afficher la page du profil utilisateur et la liste de ses abonnés */
    public function profileFollowers(User $user) {
        $this->getSharedData($user);
        return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);
    }

    /* Afficher la page du profil utilisateur et la liste de ses abonnements */
    public function profileFollowing(User $user) {
        $this->getSharedData($user);
        return view('profile-following', ['following' => $user->followingTheseUsers()->latest()->get()]);
    }

    /* Afficher le formulaire de téléchargement d'une photo de profil */
    public function showAvatarForm() {
        return view('avatar-form');
    }

    /* Sauvegarder l'avatar en bdd */
    public function storeAvatar(Request $request) {
        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);
        // user connecté
        $user = auth()->user();
        // création path unique
        $filename = $user->id . '-' . uniqid() . '.jpg';
        // redimensionner image
        $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        // stocker fichier + données dans le repertoire
        Storage::put('public/avatars/' . $filename, $imgData);

        // ancien avatar stocké en bdd
        $oldAvatar = $user->avatar;
        // mettre à jour la bdd
        $user->avatar = $filename;
        $user->save();

        // si la colonne avatar n'est pas = à l'img par défaut ça veut dire qu'il y avait vraiment une ancienne valeur, une ancienne photo alors on la supprime
        if ($oldAvatar != "/fallback-avatar.jpg") {
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        }

        return back()->with('success', 'Bravo, vous avez un nouvel avatar.');
    }

}













