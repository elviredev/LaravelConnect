<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPost
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'user_id'];

    // Relation : ce post appartient à cet user
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
