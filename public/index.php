<?php

use App\Router;

require '../vendor/autoload.php';

//A lightning fast router for PHP : Altorouter
//https://packagist.org/packages/altorouter/altorouter
$router = new AltoRouter();

$router = new App\Router(dirname(__DIR__) . '/views');

$router
    ->get('/blog', 'post/index', 'blog')
    ->get('/blog/category', 'category/show', 'category')
    ->run();