<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
    //echo "Lumen";
});

$router->group(['prefix' => 'api'], function() use($router) {

	//dd('here');
	$router->post('register-user', 'UserController@register');
	$router->post('login', 'UserController@login');
	$router->get('me', 'UserController@me');
	$router->get('refresh', 'UserController@refresh');
	$router->post('logout', 'UserController@logout');

	// post routes

	$router->post('post/store', 'PostController@store');
	$router->get('post/show/{id}', 'PostController@show');
	$router->post('post/update/{id}', 'PostController@update');
	$router->delete('post/delete/{id}', 'PostController@delete');
	$router->get('post/all', 'PostController@allPosts');
});
