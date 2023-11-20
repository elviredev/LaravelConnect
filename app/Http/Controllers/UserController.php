<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            return view('homepage-feed');
        } else {
            // si pas authentifié
            return view('homepage');
        }
    }

    /* Afficher la page du profil utilisateur */
    public function profile(User $user) {
        return view('profile-posts', ['username' => $user->username, 'posts' => $user->posts()->latest()->get(), 'postCount' => $user->posts()->count()]);
    }


}
