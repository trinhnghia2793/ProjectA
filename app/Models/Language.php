<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class Language extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'name',
        'canonical',
        'publish',
        'user_id',
        'image',
        'current',
    ];

    protected $table = 'languages';

    // Mối quan hệ với bảng PostCatalogues
    public function languages() {
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_translate', 'language_id', 'post_catalogue_id')
        ->withPivot(
            'post_catalogue_id',
            'language_id',
            'name',
            'canonical',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'description',
            'content'
        )->withTimestamps();
    }
    
}
