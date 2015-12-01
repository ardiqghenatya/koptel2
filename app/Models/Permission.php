<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    
	public $table = "permissions";

	public $primaryKey = "id";

	public $hidden = ['created_at','updated_at'];
    
	public $timestamps = true;

	public $fillable = [
	    "name",
	    "name_group",
		"slug",
		"slug_view",
		"description",
		"model"
	];

	public static $rules = [
	    "name" => "required",
		"slug" => "required"
	];

}
