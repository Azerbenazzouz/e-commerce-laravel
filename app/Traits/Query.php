<?php
namespace App\Traits;

use Carbon\Carbon;

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

    public function scopeComplexFilter($query, $complexFilter) {
        $this->handleOperator($query, $complexFilter);
        return $query;
    }

    private function handleOperator($query, $complexFilter){
        if(count($complexFilter)) {
            foreach($complexFilter as $field => $conditions) {
                foreach ($conditions as $operator => $val) {
                    switch ($operator) {
                        case 'gt':
                            $query->where($field, '>', $val);
                            break;
                        case 'gte':
                            $query->where($field, '>=', $val);
                            break;
                        case 'lt':
                            $query->where($field, '<', $val);
                            break;
                        case 'lte':
                            $query->where($field, '<=', $val);
                            break;
                    }
                }
            }
        }
    }

    private function handleDateOperator($query, $complexFilter){
        if(count($complexFilter)) {
            foreach($complexFilter as $field => $conditions) {
                foreach ($conditions as $operator => $date) {
                    switch ($operator) {
                        case 'gt':
                            $query->where($field, '>', Carbon::parse($date)->startOfDay());
                            break;
                        case 'gte':
                            $query->where($field, '>=', Carbon::parse($date)->startOfDay());
                            break;
                        case 'lt':
                            $query->where($field, '<', Carbon::parse($date)->startOfDay());
                            break;
                        case 'lte':
                            $query->where($field, '<=', Carbon::parse($date)->startOfDay());
                            break;
                        case 'between':
                            list($startDate, $endDate) = explode(',', $date);
                            // dd($startDate, $endDate);
                            $query->whereBetween($field, [
                                Carbon::parse($startDate)->startOfDay(),
                                Carbon::parse($endDate)->endOfDay()
                            ]);
                            break;
                    }
                }
            }
        }
    }

    public function scopeDateFilter($query, $dateFilter) {
        $this->handleDateOperator($query, $dateFilter);
        return $query;
    }

}