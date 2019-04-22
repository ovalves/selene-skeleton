<?php
/**
 * @copyright   2017 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2017-10-18
 */

require_once "vendor/autoload.php";

$loader = new Vindite\Loader\AppLoader;
$loader->addDirectory('App/Controllers');
$loader->addDirectory('App/Models');
$loader->addDirectory('App/Gateway');
$loader->load();

/**
 * Instanciando o framework
 */
$app = Vindite\App::getInstance();

$app->route()->middleware([
    new Vindite\Middleware\Handler\Auth,
    new Vindite\Middleware\Handler\Session
])->group(function () use ($app) {

    $app->route()->get('/callable', function () use ($app) {
        $app->json('ola mundo again');
    });
    
    $app->route()->get('/shos/{id}', 'HomeController@show');
    $app->route()->get('/show/{id}', 'HomeController@show');
    $app->route()->get('/', 'HomeController@index');
    $app->route()->post('/store', 'IndexController@usuario');
    $app->route()->patch('/code/:id', 'IndexController@index');
    $app->route()->delete('/code/:id', 'IndexController@delete');
})->run();
