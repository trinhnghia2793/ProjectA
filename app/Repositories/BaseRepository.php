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
        array $rawQuery = [],
    ) {
        // Select trong bảng
        $query = $this->model->select($column);

        return $query   
                    ->keyword($condition['keyword'] ?? null)
                    ->publish($condition['publish'] ?? null)
                    ->customWhere($condition['where'] ?? null)
                    ->customWhereRaw($rawQuery['whereRaw'] ?? null)
                    ->relationCount($relations ?? null)
                    ->customJoin($join ?? null)
                    ->customGroupBy($extend['groupBy'] ?? null)
                    ->customOrderBy($orderBy ?? null)
        // Bước cuối: Trả về & phân trang
                    ->paginate($perPage)
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
 
    // Cập nhật theo điều kiện where (đang dùng cho chuyển đổi ngôn ngữ)
    public function updateByWhere($condition = [], array $payload = []) {
        $query = $this->model->newQuery();
        foreach($condition as $key => $val) {
            $query->where($val[0], $val[1], $val[2]);
        }
        return $query->update($payload);
    }

    // Cập nhật theo where in
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
    // Thêm bảng Pivot (cái này có thời gian ngồi nghiên cứu sau)
    public function createPivot($model, array $payload = [], string $relation = '') {
        return $model->{$relation}()->attach($model->id, $payload);
    }
}
