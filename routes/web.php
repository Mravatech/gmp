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

    $router->post('/paymentUpdate', 'Auth\AuthController@fromPayvantage');




    $router->group(['middleware' => 'member'], function () use ($router) {
        $router->get('/authorize', 'Auth\AuthController@authorization');
        $router->post('/profile/update', 'Profile\ProfileController@add');
        $router->post('/profile/update/passport', 'Profile\ProfileController@updatePassport');
        $router->post('/profile/update/education', 'Profile\ProfileController@updateEducation');
        $router->post('/profile/update/experience', 'Profile\ProfileController@updateExperience');
        $router->get('/profile', 'Profile\ProfileController@profile');

        $router->group(['prefix' => 'jobs', 'namespace' => 'Jobs'], function () use ($router) {
            $router->get('/list', 'JobsController@list');
            $router->post('/apply/{uuid}', 'JobsController@apply');
        });

        $router->group(['prefix' => 'mentor', 'namespace' => 'Mentors'], function () use ($router) {
            $router->post('/request', 'MentorController@requestMentor');
            $router->get('/list', 'MentorController@myRequest');
        });

        $router->group(['prefix' => 'convention', 'namespace' => 'Convention'], function () use ($router) {
            $router->get('/list', 'ConventionController@getAllConvention');
            $router->get('/list/{year}', 'ConventionController@getConventionByYear');
            $router->get('/start', 'ConventionController@startPayment');
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

            $router->group(['prefix' => 'mentor', 'namespace' => 'Mentors'], function () use ($router) {
                $router->post('/assign', 'MentorController@assignMentor');
                $router->get('/list', 'MentorController@allRequest');
            });

            $router->group(['prefix' => 'convention', 'namespace' => 'Convention'], function () use ($router) {
                $router->get('/list', 'ConventionController@getAllConvention');
                $router->get('/list/{year}', 'ConventionController@getConventionByYear');
                $router->post('/add', 'ConventionController@createConvention');
                $router->post('/create/fee/{uuid}', 'ConventionController@createFees');
                $router->post('/start/{uuid}', 'ConventionController@startPayment');
            });



            $router->group(['prefix' => 'payment', 'namespace' => 'Payment'], function () use ($router) {
                $router->group(['prefix' => 'type'], function () use ($router) {
                    $router->post('/add', 'PaymentController@addTypes');
                    $router->post('/edit/{uuid}', 'PaymentController@editTypes');
                    $router->get('/list', 'PaymentController@listTypes');
                    $router->delete('/delete/{uuid}', 'PaymentController@deleteTypes');
                });
            });
        });
    });
});
