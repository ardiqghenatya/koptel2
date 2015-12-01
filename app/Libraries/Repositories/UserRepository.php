<?php
namespace App\Libraries\Repositories;

use App\User;
use Illuminate\Support\Facades\Schema;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;

class UserRepository extends Repository
{
    
    /**
     * Configure the Model
     *
     *
     */
    public function model() {
        return 'App\User';
    }
    
    public function getMe($user_id) {
        $me = User::query()->select('users.id', 'users.name', 'users.email', 'permissions.name as permission_name', 'permissions.slug_view')->join('role_user', 'role_user.user_id', '=', 'users.id')->join('permission_role', 'permission_role.role_id', '=', 'role_user.role_id')->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')->get();
        
        $results = [];
        $results['features'] = [];
        foreach ($me as $key => $val) {
            $results['id'] = $val->id;
            $results['name'] = $val->name;
            $results['email'] = $val->email;
            array_push($results['features'], $val->slug_view);
        }
        
        return $results;
    }
    
    public function search($input) {
        $query = User::query();
        
        $columns = Schema::getColumnListing('users');
        $attributes = array();
        
        foreach ($columns as $attribute) {
            $attributes[$attribute] = null;
            if (isset($input[$attribute]) and !empty($input[$attribute])) {
                $query->where($attribute, $input[$attribute]);
                $attributes[$attribute] = $input[$attribute];
            }
        }
        
        /*
         ** Filter
        */
        $this->filter($input, $query);
        
        /*
         ** Get count
        */
        $total = $query->count();
        
        /*
         ** Pagination
        */
        $this->pagination($input, $query);
        
        /*
         ** Order
        */
        $this->order($input, $query);
        
        return [$query->get(), $attributes, 'total' => $total];
    }
    
    public function lastUpdated() {
        $query = User::orderBy('updated_at', 'DESC')->first();
        
        if ($query) {
            return $query->updated_at->format('Y-m-d H:i:s');
        }
        
        return date("Y-m-d H:i:s");
    }
    
    private function filter($input, &$query) {
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
    
    private function pagination($input, &$query) {
        if (isset($input['offset']) && $input['offset'] > 0) {
            $query->skip($input['offset']);
        }
        
        if (isset($input['limit']) && $input['limit'] > 0) {
            $query->take($input['limit']);
        }
    }
    
    private function order($input, &$query) {
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
