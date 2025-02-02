<?php
namespace App\Traits;

use Carbon\Carbon;

trait Query{

    /**
     * Scope a query to search keyword.
     * Example: $query->keyword(['q' => 'keyword', 'field' => ['name', 'email']]);
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
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

    /**
     * Scope a query to filter data.
     * Example: $query->simpleFilter(['status' => 1, 'type' => 'admin']);
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $simpleFilter
     * @return \Illuminate\Database\Eloquent\Builder
     */
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

    /**
     * Scope a query to filter data.
     * Example: $query->complexFilter(['status' => ['gt' => 1, 'lt' => 5]]);
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $complexFilter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeComplexFilter($query, $complexFilter) {
        $this->handleOperator($query, $complexFilter);
        return $query;
    }

    /**
     * Handle operator for complex filter
     * Example: $query->complexFilter(['status' => ['gt' => 1, 'lt' => 5]]);
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $dateFilter
     * @return \Illuminate\Database\Eloquent\Builder
     */
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

    /**
     * Handle date operator for date filter
     * Example: $query->dateFilter(['created_at' => ['gt' => '2020-01-01']]);
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
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
    /**
     * Scope a query to filter date.
     * Example: $query->dateFilter(['created_at' => ['gt' => '2020-01-01']]);
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $dateFilter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateFilter($query, $dateFilter) {
        $this->handleDateOperator($query, $dateFilter);
        return $query;
    }

    public function scopePermissionFilter($query, $permission){
        $auth = auth('api')->user();
        if(isset($permission['view']) && $permission['view'] === 'own'){
            
            $query->where('user_id', $auth->id);
        }

        return $query;
    }

}