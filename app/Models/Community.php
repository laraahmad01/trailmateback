<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;
    public function members()
    {
        return $this->belongsToMany(User::class, 'community_members', 'community_id', 'member_id');
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function memberss()
    {
        return $this->hasMany(CommunityMember::class);
    }
    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'community_admins', 'community_id', 'admin_id');
    }

}
