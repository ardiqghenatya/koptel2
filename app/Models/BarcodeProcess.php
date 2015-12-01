<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarcodeProcess extends Model
{
    
    public $table = "barcode_processes";
    
    public $primaryKey = "id";
    
    public $timestamps = true;
    
    public $fillable = ["barcode", "shelf_id", "taken_id", "description", "status"];
    
    public static $rules = ["barcode" => "required", "shelf_id" => "required"];
    
    public function toArray() {
        $array = parent::toArray();
        $array['shelf'] = $this->shelf;
        return $array;
    }
    
    public function shelf() {
        return $this->belongsTo('App\Models\Shelf', 'shelf_id', 'id');
    }
}
