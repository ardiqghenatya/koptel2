<?php namespace App\Http\Middleware;

use Closure;
use DB;
use Response;

class AdminAuth {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$input = $request->all();
		if(isset($input['access_token'])){
			$owner_type = $this->getAccessTokenOwnerType($input['access_token']);
			if($owner_type && $owner_type !== 'user'){
				return Response::json(array('error'=>'access_denied','meta'=>'The resource owner or authorization server denied the request'));
			}
		}
		return $next($request);
	}

	private function getAccessTokenOwnerType($access_token){
		$access_token_info = DB::table('oauth_access_tokens')
						->join('oauth_sessions','oauth_access_tokens.session_id','=','oauth_sessions.id')
						->where('oauth_access_tokens.id',$access_token)
						->first();

		return $access_token_info->owner_type ? $access_token_info->owner_type : NULL;
	}

}
