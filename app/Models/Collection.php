<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    protected $table = 'collection';
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trail()
    {
        return $this->belongsToMany(Trail::class, 'collection_trails', 'collection_id', 'trail_id');
    }

    public function collectionTrails()
    {
        return $this->hasMany(CollectionTrails::class, 'collection_id');
    }
}