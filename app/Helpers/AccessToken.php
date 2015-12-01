<?php

namespace App\Helpers;
use Response;

use DB;

class AccessToken{

	public static function getData($access_token){
		$query = DB::table('oauth_access_tokens');
		$query->join('oauth_sessions','oauth_sessions.id','=','oauth_access_tokens.session_id');
		$query->where('oauth_access_tokens.id',$access_token);
		$data = $query->first();
		
		return $data;
	}

}