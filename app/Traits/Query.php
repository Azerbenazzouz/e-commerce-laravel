<?php
namespace App\Traits;

trait Query{

    public function scopekeyword($query, $keyword) {
        // dd($keyword);
        if(count($keyword)) {
            // dd($keyword);
            foreach($keyword['field'] as $key => $val) {
                $query->orWhere($val, 'LIKE', '%' . $keyword['q'] . '%');
            }
            // dd($query);
        }else{
            return $query->orWhere('name', 'LIKE', '%' . $keyword['q'] . '%');
        }

        return $query;
    }
}