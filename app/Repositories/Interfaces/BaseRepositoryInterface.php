<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{
    // Khai báo bắt buộc để BaseRepository tuân theo
    public function pagination(
        array $column = ['*'], 
        array $condition = [], 
        array $join = [],
        array $extend = [],
        int $perPage = 1,
        array $relations = [],
    );
    public function all();
    public function findById(int $id);
    public function create(array $payload = []);
    public function update(int $id = 0, array $payload = []);
    public function delete(int $id = 0);
    public function updateByWhereIn($whereInField = '', array $whereIn = [], array $payload = []);
    public function createLanguagePivot($model, array $payload = []);
}
