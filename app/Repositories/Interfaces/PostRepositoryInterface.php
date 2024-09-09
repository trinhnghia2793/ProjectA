<?php

namespace App\Repositories\Interfaces;

interface PostRepositoryInterface
{
    // Khai báo để chống báo lỗi
    public function pagination(
        array $column = ['*'], 
        array $condition = [], 
        int $perPage = 1,
        array $extend = [],
        array $orderBy = ['id', 'DESC'], // mặc định
        array $join = [],
        array $relations = [],
    );
    public function findById(int $id);
    public function create(array $payload = []);
    public function update(int $id = 0, array $payload = []);
    public function delete(int $id = 0);
    public function forceDelete(int $id = 0);
    public function updateByWhereIn($whereInField = '', array $whereIn = [], array $payload = []);
    public function createPivot($model, array $payload = [], string $relation = '');

    // Hmmmm
    public function getPostById(int $id = 0, $language_id = 0);
}