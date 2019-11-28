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

use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api/v1'], function (Laravel\Lumen\Routing\Router $router) {
    $router->post('/register', 'Auth\AuthController@register');
    $router->post('/login', 'Auth\AuthController@login');

    $router->group(['middleware' => 'member'], function () use ($router) {
        $router->get('/authorize', 'Auth\AuthController@authorization');
        $router->post('/profile/update', 'Profile\ProfileController@add');
        $router->get('/profile', 'Profile\ProfileController@profile');

        $router->group(['prefix' => 'job', 'namespace' => 'Job'], function () use ($router) {
            $router->get('/list', 'JobsController@list');
            $router->get('/apply/{uuid}', 'JobsController@apply');
        });
    });


    $router->group(['prefix' => 'admin'], function () use ($router) {
        $router->post('/ad_register', 'Admin\AuthController@register');
        $router->post('/login', 'Admin\AuthController@login');



        $router->group(['middleware' => 'admin'], function () use ($router) {
            $router->get('/authorize', 'Admin\AuthController@authorization');
            $router->get('/member/list', 'Admin\MemberController@list');

            $router->group(['prefix' => 'job', 'namespace' => 'Jobs'], function () use ($router) {
                $router->post('/add', 'JobsController@addJob');
                $router->post('/update/{uuid}', 'JobsController@updateJob');
                $router->get('/list', 'JobsController@list');
                $router->get('/application/{uuid}', 'JobsController@applications');
            });


            $router->group(['prefix' => 'payment', 'namespace' => 'Payment'], function () use ($router) {
                $router->group(['prefix' => 'type'], function () use ($router) {
                    $router->post('/add', 'PaymentController@addTypes');
                    $router->post('/update/{uuid}', 'PaymentController@editTypes');
                    $router->get('/list', 'PaymentController@listTypes');
                    $router->get('/delete/{uuid}', 'PaymentController@deleteTypes');
                });
            });
        });
    });
});
