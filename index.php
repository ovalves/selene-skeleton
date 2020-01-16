<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-01-17
 */

require_once "vendor/autoload.php";

$app = Selene\App\Factory::create();

$app->route()->middleware([
   new Selene\Middleware\Handler\Auth
])->group(function () use ($app) {
    $app->route()->get('/callable', function () use ($app) {
        $app->json('ola mundo again');
    });

    $app->route()->get('/', 'HomeController@index');
    $app->route()->get('/shos/{id}', 'HomeController@show');
    $app->route()->get('/show/{id}', 'HomeController@show');
    $app->route()->get('/store', 'HomeController@store');
    $app->route()->get('/login', 'HomeController@login');
    $app->route()->post('/login', 'HomeController@login');
    $app->route()->get('/logout', 'HomeController@logout');
    $app->route()->get('/logout', 'HomeController@logout');
})->run();
