<?php

namespace App\Helpers;

use App\Models\ProductCategories;

class ProductCategoriesHelper
{
    public function get($tree = 0, $createQuery= 0, $array = [], $selected = 0)
    {
        $categories = $array;

        if($createQuery)
        {
            $query = ProductCategories::query();
            $query->select(
            'ACTIVE as active',
            'WEB_ACTIVE as web_active',
            'CATEGORY as category',
            'DESCRIPT as name',
            'PARENTCAT as parent_cat'
            );

            $query->where('ACTIVE','T');
            $query->where('WEB_ACTIVE','T');

            $categories = $query->get()->toArray();    
        }

        if($tree)
        {
            return $this->getTree($categories);
        }

        return $this->getCategories($categories, 'parent_cat', null, $selected);
        
    }

    private function getCategories($array, $key = 'parent_cat', $value = null, $selected = 0){
        $results = [];

        if($selected)
        {
            $results[] = $selected; 
        }
      
        $that = $this;

        if(is_array($array))
        {
            /*
            ** Get recursive categories
            */
            foreach($array as $data){
                /*
                ** Level 1 categories
                */
                if(isset($data[$key]) && $data[$key] == $selected){
                    $results[] = $data['category'];
                    continue;
                }

                /*
                ** Recursive
                */
                if (!$selected && $data[$key] == $value) {
                    $results[] = $data['category'];
                
                    /* 
                    ** Check is categories has children
                    */
                    if($that->hasChildren($array, $key, $data['category']))
                    {
                        $children = $that->getCategories($array, 'parent_cat', $data['category']);
                        $results = array_merge($results, $children);
                    }
                }
            }
        }

        return $results;
    }

    private function getTree($array, $key = 'parent_cat', $value = null)
    {   
        $results = array();
        $that = $this;

        if (is_array($array)) {
            foreach ($array as $data) {
                if ($data[$key] == $value) {
                    $data['items'] = $that->hasChildren($array, 'parent_cat', $data['category']) ? $that->getTree($array, 'parent_cat', $data['category']) : array();
                    $results[] = $data;
                }
            }
        }

        return $results;
    }

    private function hasChildren($array, $key, $value)
    {
        if (is_array($array)) {
            foreach ($array as $data) {
                if (isset($data[$key]) && $data[$key] == $value) {
                    return true;
                }
            }
        }

        return false;
    }
}
