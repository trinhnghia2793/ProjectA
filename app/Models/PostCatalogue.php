<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

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
        'follow',
        'order',
        'user_id',
    ];

    // Khai báo tên bảng
    protected $table = 'post_catalogues';

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

    // Mối quan hệ với bảng postCatalogueLanguage
    public function post_catalogue_language() {
        return $this->hasMany(PostCatalogueLanguage::class, 'post_catalogue_id', 'id');
    }

    public static function isNodeCheck($id = 0) {
        $postCatalogue = PostCatalogue::find($id);

        if($postCatalogue->rgt - $postCatalogue->lft !== 1) {
            return false;
        }

        return true;
    }
}
