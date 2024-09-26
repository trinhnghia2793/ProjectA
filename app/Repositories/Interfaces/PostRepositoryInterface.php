<?php

namespace App\Repositories\Interfaces;

interface PostRepositoryInterface
{
    // Khai báo để chống báo lỗi bên service và controller
    public function pagination(
        array $column = ['*'], 
        array $condition = [], 
        int $perPage = 1,
        array $extend = [],
        array $orderBy = ['id', 'DESC'], // mặc định
        array $join = [],
        array $relations = [],
        array $rawQuery = [],
    );
    public function create(array $payload = []);
    public function update(int $id = 0, array $payload = []);
    // public function updateByWhere($condition = [], array $payload = []);
    public function updateByWhereIn($whereInField = '', array $whereIn = [], array $payload = []);
    public function delete(int $id = 0);
    // public function forceDelete(int $id = 0);
    // public function all();
    public function findById(int $id);
    // public function findByCondition($condition = []);
    public function createPivot($model, array $payload = [], string $relation = '');

    // Khai báo interface
    public function getPostById(int $id = 0, $language_id = 0);
}
