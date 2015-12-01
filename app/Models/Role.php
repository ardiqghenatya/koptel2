<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    
	public $table = "roles";

	public $primaryKey = "id";
    
	public $timestamps = true;

	public $fillable = [
	    "name",
		"slug",
		"description",
		"level"
	];

	public static $rules = [
	    "name" => "required",
		"slug" => "required"
	];

	public function toArray(){
		$array = parent::toArray();
		$array['permission_role'] = $this->permissionRole;
		return $array;
	}

	public function permissionRole(){
		return $this->hasMany('App\Models\PermissionRole','role_id','id');
	}

	public function getPermissionRole(){
		$query = \App\Models\PermissionRole::query()->where('role_id',$this->id)->get();
		$results = [];
		foreach($query as $key => $val){
			$results[$key] = $val->permission->slug_view;
		}
		return $results;
	}	

}
