<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFollow
 */
class Follow extends Model
{
    use HasFactory;

    /**
     *  Un Follow appartient à un utilisateur qui fait ce suivi (qui s'est abonné
     * à un profil)
     * Permet de récupèrer username et avatar de l'utilisateur dans la table
     * "users"
     */
    public function userDoingTheFollowing() {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Un Follow appartient à un utilisateur qui est suivi (fait l'objet d'un
     * abonnement)
     * Permet de récupèrer username et avatar de l'utilisateur dans la table
     * "users"
     */
    public function userBeingFollowed() {
        return $this->belongsTo(User::class, 'followeduser');
    }
}
