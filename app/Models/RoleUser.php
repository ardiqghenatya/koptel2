<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    
	public $table = "role_user";

	public $primaryKey = "id";
    
	public $timestamps = true;

	public $fillable = [
	    "role_id",
		"user_id"
	];

	public static $rules = [
	    "role_id" => "required",
		"user_id" => "required"
	];

	public function toArray(){
		$array = parent::toArray();
		$array['permission_role'] = $this->permission_role;
		$array['user_detail'] = $this->user;
		$array['role_detail'] = $this->roles;
		return $array;
	}

	public function user(){
		return $this->hasOne('App\User','id','user_id');
	}

	public function permission_role(){
		return $this->hasOne('App\Models\PermissionRole','role_id','role_id');
	}

	public function roles(){
		return $this->belongsTo('App\Models\Role','role_id','id');
	}

}
