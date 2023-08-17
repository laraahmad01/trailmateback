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

    use HasFactory;
}
