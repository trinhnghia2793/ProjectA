<?php

namespace App\Repositories\Interfaces;

interface ProvinceRepositoryInterface
{
    public function all();
    public function findById(int $id);
}
