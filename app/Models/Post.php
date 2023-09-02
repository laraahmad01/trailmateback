<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'community_id',
        'user_id',
        'image_url',
        'description',
        'date',
        'location',
        'person_tag',
    ];

    // app/Models/Post.php

public function taggedUser()
{
    return $this->belongsTo(User::class, 'person_tag', 'firstname', 'lastname');
}
public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    use HasFactory;
}
