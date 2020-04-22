<?php

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require '../vendor/autoload.php';

// Display errors and exceptions in a less painful way.
// http://filp.github.io/whoops/
$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

// Redirect if number of page is 1 without this parameter
if (isset($_GET['page']) && $_GET['page'] === '1')
{
    $uri = $_SERVER['REQUEST_URI'];

    // Left part of URI
    explode('?', $_SERVER['REQUEST_URI'])[0];

    $get = $_GET;
    unset($get['page']);
    $query = http_build_query($get);
    if(empty($query))
    {
        $uri = $uri . '?' .$query;
    }
    http_response_code(301);
    header('Location' . $uri);
    exit();
}

//A lightning fast router for PHP : Altorouter
//https://packagist.org/packages/altorouter/altorouter
$router = new AltoRouter();

$router = new App\Router(dirname(__DIR__) . '/views');

$router
    ->get('/', 'post/index', 'home')
    ->get('/blog/category/[*:slug]-[i:id]', 'category/show', 'category')
    ->get('/blog/[*:slug]-[i:id]', 'post/show', 'post')
    ->get('/admin', 'admin/post/index', 'admin_posts')
    ->match('/admin/post[i:id]', 'admin/post/edit', 'admin_post')
    ->post('/admin/post[i:id]/delete', 'admin/post/delete', 'admin_post_delete')
    ->get('/admin/post/new', 'admin/post/new', 'admin_post_new')
    ->run();