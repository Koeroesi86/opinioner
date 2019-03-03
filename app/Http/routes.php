<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
* https://github.com/xinax/laravel-gettext
Route::get('/lang/{locale?}', [
	'as'	=> 'lang',
	'uses'	=> 'LanguageController@changeLang'
]);
*/


Event::subscribe('App\Events\EventHandler');

Route::group(['prefix' => 'auth'], function () {
    // route to show the login form
    Route::get('login', ['uses' => 'Auth\AuthController@showLogin']);

    // route to process the form
    Route::post('login', ['uses' => 'Auth\AuthController@doLogin']);

    // route to show the login form
    Route::get('logout', ['uses' => 'Auth\AuthController@doLogout']);

    // route to show the login form
    Route::get('register', ['uses' => 'Auth\AuthController@showRegister']);

    // route to process the form
    Route::post('register', ['uses' => 'Auth\AuthController@doRegister']);

    Route::get('register/verify/{emailAddress}/{confirmationCode}', [
        'as' => 'email_confirmation',
        'uses' => 'Auth\AuthController@doConfirm'
    ]);

    Route::get('password-reminder', ['uses' => 'Auth\AuthController@showForgottenPassword']);
    Route::post('password-reminder', ['uses' => 'Auth\AuthController@doForgottenPassword']);

    Route::get('forgotten-password/{emailAddress}/{forgottenCode}', [
        'as' => 'forgotten-password',
        'uses' => 'Auth\AuthController@showChangePassword'
    ]);

    Route::match(['GET', 'POST', 'PUT'], 'change-password', [
        'as' => 'change-password',
        'uses' => 'Auth\AuthController@showChangePassword'
    ]);
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('{any?}', [
        'uses' => 'AdminController@index',
        'as' => 'admin'
    ])->where('any', '(.*)');
});

// route to AJAX requests
Route::any('ajax', ['uses' => 'AJAXController@render']);

Route::group(['prefix' => 'api', 'guard' => 'auth'], function () {
    Route::resource('post', 'Api\PostController');
    Route::resource('post-relation', 'Api\PostRelationController');
    Route::resource('settings', 'Api\SettingsController');
    Route::resource('file', 'Api\FileController');
});

//route to base Page controller
Route::any('{page}', 'PageController@index')->where('page', '(.*)');
