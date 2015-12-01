<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class PermissionRole extends Model
{
    
	public $table = "permission_role";

	public $primaryKey = "id";

	public $hidden = ['created_at','updated_at'];
    
	public $timestamps = true;

	public $fillable = [
	    "permission_id",
		"role_id"
	];

	public static $rules = [
	    "permission_id" => "required",
		"role_id" => "required"
	];

	public function toArray(){
		$array = parent::toArray();
		$array['permission'] = $this->permission;
		return $array;
	}

	public function permission(){
		return $this->belongsTo('App\Models\Permission','permission_id','id');
	}

}
