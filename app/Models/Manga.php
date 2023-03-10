<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Jenssegers\Mongodb\Eloquent\Model;

class Manga extends Model
{
    use HasFactory;

    // protected $connection = 'mongodb2';
    // protected $collection = 'manga';
    // protected $guarded = [];

    protected $fillable = [
        'mangadex_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
