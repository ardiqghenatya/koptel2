<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		//'App\Http\Middleware\VerifyCsrfToken',
		//'App\Http\Middleware\AdminAuth',
		'LucaDegasperi\OAuth2Server\Middleware\OAuthExceptionHandlerMiddleware'
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => 'App\Http\Middleware\Authenticate',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',
		'oauth' => 'LucaDegasperi\OAuth2Server\Middleware\OAuthExceptionHandlerMiddleware',
		'csrf' => 'App\Http\Middleware\VerifyCsrfToken',
		'admin.auth' => 'App\Http\Middleware\AdminAuth',
		'role' => \Bican\Roles\Middleware\VerifyRole::class,
	    'permission' => \Bican\Roles\Middleware\VerifyPermission::class,
	    'level' => \Bican\Roles\Middleware\VerifyLevel::class,
	    'oauth_permission' => 'App\Http\Middleware\VerifyPermission',
	];

}
