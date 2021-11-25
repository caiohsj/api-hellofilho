<?php

use Illuminate\Http\Request;

/** @var \Laravel\Lumen\Routing\Router $router */

$router->post('/api/v1/sign_in', 'AuthController@signIn');

$router->group(['prefix' => 'api/v1', 'middleware' => 'auth'], function () use ($router) {
    $router->get('students', function (Request $request) {
        return auth()->user()->students();
    });
});
