<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Jenssegers\Mongodb\Eloquent\Model;

class Covers extends Model
{
    use HasFactory;

    // protected $connection = 'mongodb2';
    // protected $collection = 'covers';
    // protected $guarded = [];

    protected $fillable = [
        'mangadex_chapter_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
