<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionTrails extends Model
{
    use HasFactory;
    protected $fillable = [
        'collection_id',
        'trail_id',
        // Add any other fields you want to allow mass assignment for here
    ];

}
