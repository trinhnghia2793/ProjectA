<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCatalogue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
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

    // Mối quan hệ với bảng languages
    public function languages() {
        return $this->belongsToMany(Language::class, 'post_catalogue_language', 'post_catalogue_id', 'language_id')
        ->withPivot(
            'name',
            'canonical',
            'meta_title',
            'meta_keyword',
            'meta-description',
            'description',
            'content'
        )->withTimestamps();
    }
}
