<?php
namespace App\Traits;

trait Query{

    public function scopekeyword($query, $keyword) {
        if(count($keyword)) {
            foreach($keyword['field'] as $key => $val) {
                $query->orWhere($val, 'LIKE', '%' . $keyword['q'] . '%');
            }
        }else{
            return $query->orWhere('name', 'LIKE', '%' . $keyword['q'] . '%');
        }
        return $query;
    }

    public function scopeSimpleFilter($query, $simpleFilter) {
        if(count($simpleFilter)) {
            foreach($simpleFilter as $key => $val) {
                if($val !== 0 && !empty($val) && !is_null($val)) {
                    $query->where($key, $val);
                }
            }
        }
        return $query;
    }
}