<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * Accesseur permettant de rendre l'image du profil (avatar) dynamique
     * @return Attribute
     */
    protected function avatar(): Attribute {
        return Attribute::make(get: function ($value) {
            // si value (si colonne avatar) est vide on veut image par défaut sinon utiliser image en bdd
            return $value ? '/storage/avatars/' . $value : '/fallback-avatar.jpg';
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relation un user peut avoir plusieurs articles
    public function posts() {
        return $this->hasMany(Post::class, 'user_id');
    }

    // Relation un user peut avoir plusieurs abonnés
    public function followers() {
        return $this->hasMany(Follow::class, 'followeduser');
    }

    // Relation un user peut avoir plusieurs abonnements donc suivre plusieurs utilisateurs
    public function followingTheseUsers() {
        return $this->hasMany(Follow::class, 'user_id');
    }

    // Relation entre un user et les posts publiés par les utilisateurs qu'il suit
    public function feedPosts() {
        return $this->hasManyThrough(Post::class, Follow::class, 'user_id', 'user_id', 'id', 'followeduser');
    }
}
