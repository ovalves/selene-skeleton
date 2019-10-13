<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-01-17
 */

require_once "vendor/autoload.php";

$loader = new Vindite\Loader\AppLoader;
$loader->addDirectory('App/Controllers');
$loader->addDirectory('App/Models');
$loader->addDirectory('App/Gateway');
$loader->addDirectory('App/Config');
$loader->load();

/**
 * Instanciando o framework
 */
$app = Vindite\App::getInstance();

$app->route()->middleware([
    new Vindite\Middleware\Handler\Auth
])->group(function () use ($app) {

    $app->route()->get('/callable', function () use ($app) {
        $app->json('ola mundo again');
    });

    $app->route()->get('/shos/{id}', 'HomeController@show');
    $app->route()->get('/show/{id}', 'HomeController@show');
    $app->route()->get('/store', 'HomeController@store');
    $app->route()->get('/login', 'HomeController@login');
    $app->route()->post('/logout', 'HomeController@logout');
})->run();
