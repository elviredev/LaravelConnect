<?php

namespace App\Http\Controllers;

use App\Events\OurExampleEvent;
use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

            // exemple évènement
            event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'login']));

            // redirect & message
            return redirect('/')->with('success', 'Vous êtes maintenant connecté 🦄');
        } else {
            return redirect('/')->with('failure', 'Connexion invalide ❌. Veuillez réessayer.');
        }
    }

    /* Se connecter via l'API en tant qu'utilisateur */
    public function loginApi(Request $request) {
        // vérifier que les champs sont remplis
        $incomingFields = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // vérifier que username + mdp sont corrects
        if (auth()->attempt($incomingFields)) {
            $user = User::where('username', $incomingFields['username'])->first();
            // créé un jeton pour cet utilisateur
            $token = $user->createToken('ourapptoken')->plainTextToken;
            return $token;
        }
        return 'Désolé!!!';
    }

    /* Se déconnecter */
    public function logout() {
        // exemple évènement
        event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'logout']));
        // deconnexion
        auth()->logout();
        // redirect et message
        return redirect('/')->with('success', 'Vous êtes maintenant déconnecté 🦉');
    }

    /*
     * Modèle de vue à afficher selon que le user est connecté ou non
     */
    public function showCorrectHomepage() {
        // si user authentifié
        if (auth()->check()) {
            return view(
                'homepage-feed',
                ['posts' => auth()->user()->feedPosts()->latest()->paginate(5)]
            );
        } else {
            // Cache pour le nb d'articles écrits par nos utilisateurs
            $postCount = Cache::remember('postCount', 20, function() {
                // sleep(5);
                return Post::count();
            });
            // si pas authentifié
            return view('homepage', ['postCount' => $postCount]);
        }
    }

    /*
     * Fonction commune aux 3 méthodes de controller profile, profileFollowers
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

    /*
     * Afficher la page du profil utilisateur et la liste de ses articles
     * Mode SPA
     */
    public function profileRaw(User $user) {
        return response()->json(['theHTML' => view('profile-posts-only', ['posts' => $user->posts()->latest()->get()])->render(), 'docTitle' => "Profile de " . $user->username]);
    }

    /* Afficher la page du profil utilisateur et la liste de ses abonnés */
    public function profileFollowers(User $user) {
        $this->getSharedData($user);
        return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);
    }

    /*
     * Afficher la page du profil utilisateur et la liste de ses abonnés
     * Mode SPA
     */
    public function profileFollowersRaw(User $user) {
        return response()->json(['theHTML' => view('profile-followers-only', ['followers' => $user->followers()->latest()->get()])->render(), 'docTitle' => "Abonnés de " . $user->username]);
    }

    /* Afficher la page du profil utilisateur et la liste de ses abonnements */
    public function profileFollowing(User $user) {
        $this->getSharedData($user);
        return view('profile-following', ['following' => $user->followingTheseUsers()->latest()->get()]);
    }

    /*
     * Afficher la page du profil utilisateur et la liste de ses abonnements
     * Mode SPA
     */
    public function profileFollowingRaw(User $user) {
        return response()->json(['theHTML' => view('profile-following-only', ['following' => $user->followingTheseUsers()->latest()->get()])->render(), 'docTitle' => "Abonnements de " . $user->username]);
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













