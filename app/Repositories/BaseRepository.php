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

            // phần where mở rộng (dùng cho những thứ khác)
            if(isset($condition['where']) && count($condition['where'])) {
                foreach($condition['where'] as $key => $val) {
                    $query->where($val[0], $val[1], $val[2]);
                }
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

    // attach: thêm một bản ghi vào bảng pivot (bảng pivot là bảng sinh ra từ mối quan hệ n-n)
    // detach: ngược lại
    public function createLanguagePivot($model, array $payload = []) {
        return $model->languages()->attach($model->id, $payload);
    }
}
