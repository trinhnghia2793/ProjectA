<?php

namespace App\Repositories\Interfaces;

interface LanguageRepositoryInterface
{
    // Khai báo để chống báo lỗi
    public function pagination(
        array $column = ['*'], 
        array $condition = [], 
        array $join = [],
        array $extend = [],
        int $perPage = 1,
    );
    public function findById(int $id);
    public function create(array $payload = []);
    public function update(int $id = 0, array $payload = []);
    public function delete(int $id = 0);
    public function forceDelete(int $id = 0);
    public function updateByWhereIn($whereInField = '', array $whereIn = [], array $payload = []);
}
