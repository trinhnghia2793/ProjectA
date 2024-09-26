<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class UserCatalogue extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'name',
        'description',
        'publish',
    ];

    protected $table = 'user_catalogues';

    // Mối quan hệ với bảng users
    public function users() {
        return $this->hasMany(User::class, 'user_catalogue_id', 'id');
    }

    // Mối quan hệ với bảng permissions
    public function permissions() {
        return $this->belongsToMany(Permission::class, 'user_catalogue_permission', 'user_catalogue_id', 'permission_id');
    }
}
