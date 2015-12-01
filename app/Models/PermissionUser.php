<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionUser extends Model
{
    
	public $table = "permission_user";

	public $primaryKey = "id";
    
	public $timestamps = true;

	public $fillable = [
	    "permission_id",
		"user_id"
	];

	public static $rules = [
	    "permission_id" => "required",
		"user_id" => "required"
	];

}
