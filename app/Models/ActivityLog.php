<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{

	public $table = "activity_log";

	public $primaryKey = "id";
    
	protected $dates = ['deleted_at'];

	public $timestamps = true;

	public $fillable = [
	    "user_id",
		"model_id",
		"data_id",
		"text",
		"ip_address"
	];

	public static $rules = [
	    "text" => "required",
		"ip_address" => "required"
	];

	public function toArray()
	{
		$array = parent::toArray();

		$text = json_decode($this->text, true);

		$array['text'] = $text['message'];
		$array['log_data'] = $text['data'];

		return $array;
	}

}
