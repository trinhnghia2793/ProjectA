<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Base;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(
        Model $model
    )
    {
        $this->model = $model;
    }

    // Truy vấn & phân trang
    public function pagination(
        array $column = ['*'], 
        array $condition = [], 
        array $join = [],
        array $extend = [],
        int $perPage = 1,
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
        if(!empty($join)) {
            $query->join(...$join);
        }

        // Trả về & phân trang
        return $query->paginate($perPage)
                    ->withQueryString()
                    ->withPath(env('APP_URL').$extend['path']);
    }

    // Tạo
    public function create(array $payload = []) {
        $model = $this->model->create($payload);
        return $model->fresh();
    }

    // Cập nhật
    public function update(int $id = 0, array $payload = []) {
        $model = $this->findById($id);
        return $model->update($payload);
    }

    // Cập nhật theo where
    public function updateByWhereIn($whereInField = '', array $whereIn = [], array $payload = []) {
        return $this->model->whereIn($whereInField, $whereIn)->update($payload);
    }

    // Xóa (hình như là xoá mềm thì phải)
    public function delete(int $id = 0) {
        return $this->findById($id)->delete();
    }

    // Xóa bắt buộc
    public function forceDelete(int $id = 0) {
        return $this->findById($id)->forceDelete();
    }

    // Tất cả (chắc chả thèm dùng)
    public function all() {
        return $this->model->all();
    }

    // Tìm theo Id
    public function findById(
        int $modelId,
        array $column = ['*'],
        array $relation = [],
    ) {
        return $this->model->select($column)->with($relation)->findOrFail($modelId);
    }
}
