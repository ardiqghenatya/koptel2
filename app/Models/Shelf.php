<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    
    public $table = "shelves";
    
    public $primaryKey = "id";
    
    public $timestamps = true;
    
    public $fillable = ["code", "description", "status"];
    
    public static $rules = ["code" => "required|unique:shelves"];
    
    public function toArray() {
        $array = parent::toArray();
        $array['transactions'] = $this->transactions;
        return $array;
    }
    
    public function transactions() {
        return $this->hasMany('App\Models\BarcodeProcess', 'id', 'shelf_id');
    }
}
