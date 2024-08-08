<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCatalogue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parentId',
        'lft', // left
        'rgt', // right
        'level',
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'user_id',
    ];

    protected $table = 'languages';

    // Mối quan hệ
}
