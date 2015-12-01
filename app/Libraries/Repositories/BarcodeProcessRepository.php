<?php
namespace App\Libraries\Repositories;

use App\Models\BarcodeProcess;
use Illuminate\Support\Facades\Schema;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;

class BarcodeProcessRepository extends Repository
{
    
    /**
     * Configure the Model
     *
     *
     */
    public function model() {
        return 'App\Models\BarcodeProcess';
    }
    
    public function search($input) {
        $query = BarcodeProcess::query();
        
        $query->select('barcode_processes.*');
        $query->leftJoin('shelves', 'barcode_processes.shelf_id', '=', 'shelves.id');
        
        $columns = Schema::getColumnListing('barcode_processes');
        $attributes = array();
        
        foreach ($columns as $attribute) {
            $attributes[$attribute] = null;
            if (isset($input[$attribute]) and !empty($input[$attribute])) {
                $query->where($attribute, $input[$attribute]);
                $attributes[$attribute] = $input[$attribute];
            }
        }
        
        /**
         * Filter
         */
        $this->filter($input, $query);
        
        /**
         * Get count
         */
        $total = $query->count();
        
        /**
         * Pagination
         */
        $this->pagination($input, $query);
        
        /**
         * Order
         */
        $this->order($input, $query);
        
        return [$query->get(), $attributes, 'total' => $total];
    }
    
    public function lastUpdated() {
        $query = BarcodeProcess::orderBy('updated_at', 'DESC')->first();
        
        if ($query) {
            return $query->updated_at->format('Y-m-d H:i:s');
        }
        
        return date("Y-m-d H:i:s");
    }
    
    public function filter($input, &$query) {
        if (isset($input['filter'])) {
            $filters = json_decode($input['filter']);
            
            if (count($filters)) {
                foreach ($filters as $filter) {
                    switch ($filter->operator) {
                        case 'like':
                            $query->where($filter->field, $filter->operator, '%' . $filter->value . '%');
                            break;

                        case 'between':
                            $query->whereBetween($filter->field, [$filter->value[0], $filter->value[1]]);
                            break;

                        case 'notbetween':
                            $query->whereNotBetween($filter->field, [$filter->value[0], $filter->value[1]]);
                            break;

                        case 'in':
                            $query->whereIn($filter->field, $filter->value);
                            break;

                        case 'notin':
                            $query->whereNotIn($filter->field, $filter->value);
                            break;

                        default:
                            $query->where($filter->field, $filter->operator, $filter->value);
                            break;
                    }
                }
            }
        }
    }
    
    public function pagination($input, &$query) {
        if (isset($input['offset']) && $input['offset'] > 0) {
            $query->skip($input['offset']);
        }
        
        if (isset($input['limit']) && $input['limit'] > 0) {
            $query->take($input['limit']);
        }
    }
    
    public function order($input, &$query) {
        if (isset($input['order'])) {
            $orders = json_decode($input['order']);
            
            if (count($orders)) {
                foreach ($orders as $order) {
                    $query->orderBy($order->field, $order->sort);
                }
            }
        }
    }
}
