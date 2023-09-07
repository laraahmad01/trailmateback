<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
    ];

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

    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_members', 'member_id', 'community_id');
    }
    public function posts() {
        return $this->hasMany(Post::class);
    }
    public function likes() {
        return $this->hasMany(Like::class);
    }
    public function comments() {
        return $this->hasMany(Comment::class);
    }
    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
    
    public function hikingHistory()
    {
        return $this->hasMany(HikingHistory::class);
    public function adminCommunities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class, 'community_admins', 'admin_id', 'community_id');
    }
    
}
