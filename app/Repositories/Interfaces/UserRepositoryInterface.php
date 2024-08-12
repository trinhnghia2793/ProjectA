<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function pagination(
        array $column = ['*'], 
        array $condition = [], 
        array $join = [],
        array $extend = [],
        int $perPage = 1,
        array $relations = [],
        array $orderBy = [],
    );
    public function findById(int $id);
    public function create(array $payload = []);
    public function update(int $id = 0, array $payload = []);
    public function delete(int $id = 0);
    public function forceDelete(int $id = 0);
    public function updateByWhereIn($whereInField = '', array $whereIn = [], array $payload = []);
}
// Thật ra thì mấy cái RepositoryInterface cũng chả cần thiết khai báo làm gì, chỉ là khai báo để chống báo lỗi thôi.