<?php

namespace App\Http\Middleware;

use Response;
use Closure;
use Bican\Roles\Exceptions\PermissionDeniedException;

use LucaDegasperi\OAuth2Server\Authorizer;
use App\User;

class VerifyPermission
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @return void
     */
    public function __construct(Authorizer $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param int|string $permission
     * @return mixed
     * @throws \Bican\Roles\Exceptions\PermissionDeniedException
     */
    public function handle($request, Closure $next)
    {
        if(!$this->authorizer->getChecker()->getAccessToken()){
            return $next($request);
        }

        $user_id = $this->authorizer->getResourceOwnerId();
        $user = User::find($user_id);

        $route = $request->route()->getAction();

        if(!isset($route['as']))
        {
            return $next($request);
        }

        $route = explode("." ,$route['as'] , 3);
        $route = $route[2];

        if ($user && $user->can($route)) {
            return $next($request);
        }

        return Response::json(array('error'=>'access_denied','meta'=>"You don't have permission to access this page."), 403);
    }
}
