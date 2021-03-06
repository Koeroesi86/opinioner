<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
//use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
//use Illuminate\Cookie\Middleware\EncryptCookies;
//use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
//use Illuminate\Session\Middleware\StartSession;
//use Illuminate\View\Middleware\ShareErrorsFromSession;
//use Xinax\LaravelGettext\Middleware\GettextMiddleware;

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
		'App\Http\Middleware\VerifyCsrfToken',
		'Xinax\LaravelGettext\Middleware\GettextMiddleware',
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
	];

}
