<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;
    protected $table = 'communities';
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function members()
    {
        return $this->hasMany(CommunityMember::class);
    }
    public function memberss()
    {
        return $this->hasMany(CommunityMember::class);
    }
    public function admins()
    {
        return $this->hasMany(CommunityMember::class)->where('is_admin', true);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
