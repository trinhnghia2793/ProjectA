<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    // Khai báo chống báo lỗi
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
    public function findById(int $id);
    public function create(array $payload = []);
    public function update(int $id = 0, array $payload = []);
    public function delete(int $id = 0);
    public function forceDelete(int $id = 0);
    public function updateByWhereIn($whereInField = '', array $whereIn = [], array $payload = []);
}