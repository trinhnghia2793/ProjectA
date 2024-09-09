<?php

namespace App\Repositories;

use App\Models\PostCatalogue;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface;
use App\Repositories\BaseRepository;

class PostCatalogueRepository extends BaseRepository implements PostCatalogueRepositoryInterface
{

    protected $model;

    public function __construct(
        PostCatalogue $model
    ){
        $this->model = $model;   
    }

    // Truy vấn & phân trang (viết riêng)
    public function pagination(
        array $column = ['*'], 
        array $condition = [], 
        int $perPage = 1,
        array $extend = [],
        array $orderBy = ['id', 'DESC'], // mặc định
        array $join = [],
        array $relations = [],
    ) {
        $query = $this->model->select($column)->where(function($query) use ($condition) {
            // Tìm kiếm theo keyword
            if(isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where('name', 'LIKE', '%'.$condition['keyword'].'%');
            }

            // Tìm kiếm theo tình trạng publish
            if(isset($condition['publish']) && $condition['publish'] != 0) {
                $query->where('publish', '=', $condition['publish']);
            }

            return $query;
        });

        // Mối quan hệ giữa các bảng
        if(isset($relations) && !empty($relations)) {
            foreach ($relations as $relation) {
                $query->withCount($relation);
            }
        }

        // Join giữa các bảng
        if(isset($join) && is_array($join) && count($join)) {
            foreach($join as $key => $val) {
                $query->join($val[0], $val[1], $val[2], $val[3]);
            }    
        }

        // Order by
        if(isset($orderBy) && !empty($orderBy)) {
            $query->orderBy($orderBy[0], $orderBy[1]);
        }

        // Bước cuối: Trả về & phân trang
        return $query->paginate($perPage)
                    ->withQueryString()
                    ->withPath(env('APP_URL').$extend['path']);
    }

    // Làm lấy ra PostCatalogue dựa trên id
    public function getPostCatalogueById(int $id = 0, $language_id = 0) {
        return $this->model->select([
                        'post_catalogues.id', 
                        'post_catalogues.parent_id', 
                        'post_catalogues.image',
                        'post_catalogues.image',
                        'post_catalogues.icon',
                        'post_catalogues.album',
                        'post_catalogues.publish',
                        'post_catalogues.follow',
                        'tb2.name',
                        'tb2.description',
                        'tb2.content',
                        'tb2.meta_title',
                        'tb2.meta_keyword',
                        'tb2.meta_description',
                        'tb2.canonical',
                    ])
                    ->join('post_catalogue_language as tb2', 'tb2.post_catalogue_id', '=', 'post_catalogues.id')
                    ->where('tb2.language_id', '=', $language_id)
                    ->findOrFail($id);
                            
    }

}
