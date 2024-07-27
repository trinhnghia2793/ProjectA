<?php

namespace App\Repositories\Interfaces;

interface DistrictRepositoryInterface
{
    public function all();
    public function findDistrictByProvinceId(int $province_id);
    public function findById(int $id);
}
