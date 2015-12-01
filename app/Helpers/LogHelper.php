<?php

namespace App\Helper;

use Input;
use Illuminate\Support\Str;

use App\Models\ActivityLog;

trait LogHelper
{
	/**
	 * Get the message that needs to be logged for the given event name.
	 *
	 * @param string $eventName
	 * @return string
	 */
	public function getActivityDescriptionForEvent($eventName)
	{
		$model_name = Str::studly($this->getTable());

		$activity = '';
		$logs = [];
		$is_trigger = false;

	    if ($eventName == 'created')
	    {
	    	$logs['message'] = $model_name.' "' . $this->id . '" was created';
	    	$logs['data'] = $this->getAttributes();
	    	$logs['data']['id'] = $this->id;

	    	$is_trigger = true;
	    }

	    if ($eventName == 'updated')
	    {
	    	$logs['message'] = $model_name.' "' . $this->id . '" was updated';
	    	
	    	$logs['data']['id'] = $this->id;
	    	$logs['data']['before'] = [];
	    	$logs['data']['after'] = [];

	    	$differents = array_diff($this->getOriginal(), $this->getAttributes());
	    	$original = $this->getOriginal();

	    	foreach ($differents as $key => $value) {
	    		$logs['data']['before'][$key] = $original[$key];
	    		$logs['data']['after'][$key] = $this->$key;
	    	}

	    	$is_trigger = true;
	    }

	    if ($eventName == 'deleted')
	    {
	    	$logs['message'] = $model_name.' "' . $this->id . '" was deleted';
	    	$logs['data']['id'] = $this->id;

	    	$is_trigger = true;
	    }

	    $results['logs'] = json_encode($logs);
	    $results['attributes'] = ['model_id'=>$model_name, 'data_id'=>$this->id];

	    if($is_trigger){
		    return $results;
	    }
	}

	public function logs()
	{
		$model_id = Str::studly($this->getTable());
		$data_id = $this->id;

		$logs = Activitylog::where('model_id', $model_id)->where('data_id', $data_id)->orderBy('id','desc')->get();

		return $logs->toArray();
	}

	public function toArray()
	{
		$array = parent::toArray();

		$access_token = Input::get('access_token');

		if($access_token)
		{
			\Authorizer::getChecker()->isValidRequest(true, $access_token);	
		}

		$user_type = \Authorizer::getChecker()->getAccessToken() ? \Authorizer::getResourceOwnerType() : false;
		
		if($user_type == 'user')
		{
			$array['logs'] = $this->logs();	
		}

		return $array;
	}
}