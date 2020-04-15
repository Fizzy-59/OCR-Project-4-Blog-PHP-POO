<?php
use App\Router;

require '../vendor/autoload.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

//A lightning fast router for PHP : Altorouter
//https://packagist.org/packages/altorouter/altorouter
$router = new AltoRouter();

$router = new App\Router(dirname(__DIR__) . '/views');

$router
    ->get('/blog', 'post/index', 'blog')
    ->get('/blog/category', 'category/show', 'category')
    ->run();