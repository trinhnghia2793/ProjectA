<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Traits\QueryScopes;

class Post extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'image',
        'album',
        'publish',
        'follow',
        'order',
        'user_id',
        'post_catalogue_id',
    ];

    // Khai báo tên bảng
    protected $table = 'posts';

    // Mối quan hệ với bảng languages
    public function languages() {
        return $this->belongsToMany(Language::class, 'post_language', 'post_id', 'language_id')
        ->withPivot(
            'name',
            'canonical',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'description',
            'content'
        )->withTimestamps();
    }

    // Mối quan hệ với bảng post_catalogues
    public function post_catalogues(){
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_post' , 'post_id', 'post_catalogue_id');
    }
}