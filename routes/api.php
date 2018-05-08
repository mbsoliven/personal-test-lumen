<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', [], function ($api) {

    $api->group(['prefix' => 'auth'], function($api) {
        $api->post('{model}/login', ['uses' => 'App\Http\Controllers\AuthController@authenticate']);
    });

    $api->group(['prefix' => 'v1', 'middleware' => 'jwt.auth' ], function($api) {
        $api->group(['prefix' => 'courses'], function($api) {
            $api->get('/', ['uses' => 'App\Http\Controllers\CoursesController@index']);
        });
    });
});
