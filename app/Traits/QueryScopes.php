<?php

// TỐI ƯU CODE CHO REPOSITORY

// Đã khai báo lại chỗ này
namespace App\Traits;

trait QueryScopes
{

    // Scope Keyword: Tìm kiếm theo keyword
    public function scopeKeyword($query, $keyword) {
        if(!empty($keyword)) {
            $query->where('name', 'LIKE', '%'.$keyword.'%');
        }
        return $query;
    }

    // Scope Publish: Tìm kiếm theo trình trạng Publish
    public function scopePublish($query, $publish) {
        if(!empty($publish)) {
            $query->where('publish', '=', $publish);
        }
        return $query;
    }

    // Scope Custom Where: Phần where mở rộng dùng cho những thứ khác
    public function scopeCustomWhere($query, $where = []) {
        if(count($where)) {
            foreach($where as $key => $val) {
                $query->where($val[0], $val[1], $val[2]);
            }
        }
        return $query;
    }

    // Raw Query: thêm vào từng câu truy vấn chay bắt được từ bên service
    public function scopeCustomWhereRaw($query, $rawQuery = []) {
        if(!empty($rawQuery)) {
            foreach($rawQuery as $key => $val) {
                $query->whereRaw($val[0], $val[1]);
            }
        }
        return $query;
    }

    // Mối quan hệ giữa các bảng
    public function scopeRelationCount($query, $relation) {
        if(!empty($relation)) {
            foreach ($relation as $item) {
                $query->withCount($item);
            }
        }
        return $query;
    }

    // Mối quan hệ giữa các bảng
    public function scopeRelation($query, $relation) {
        if(!empty($relation)) {
            foreach ($relation as $item) {
                $query->with($item);
            }
        }
        return $query;
    }

    // Join giữa các bảng
    public function scopeCustomJoin($query, $join) {
        if(!empty($join)) {
            foreach($join as $key => $val) {
                $query->join($val[0], $val[1], $val[2], $val[3]);
            }    
        }
        return $query;
    }

    // Group by
    public function scopeCustomGroupBy($query, $groupBy) {
        if(!empty($groupBy)) {
            $query->groupBy($groupBy);
        }
        return $query;
    }

    // Order by
    public function scopeCustomOrderBy($query, $orderBy) {
        if(isset($orderBy) && !empty($orderBy)) {
            $query->orderBy($orderBy[0], $orderBy[1]);
        }
        return $query;
    }

}