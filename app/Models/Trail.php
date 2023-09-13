<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trail extends Model
{
    use HasFactory;
    protected $table = 'trail';
    public function collection()
    {
        return $this->belongsToMany(Collection::class, 'collection_trails');
    }

    public function hikingHistory()
    {
        return $this->hasMany(HikingHistory::class);
    }
}
