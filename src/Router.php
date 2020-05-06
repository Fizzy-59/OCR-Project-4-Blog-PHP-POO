<?php

namespace App;

use AltoRouter;
use App\Table\Exception\NotFoundException;
use Exception;

class Router
{

    public static $router;
    private $viewPath;

    /**
     * Router constructor.
     * @param string $viewPath
     */
    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        self::$router = new AltoRouter();
    }

    public static function getInstance ()
    {
        return self::$router ;
    }

    public function get( $url,  $view, ?string $name = null): self
    {
        self::$router->map('GET', $url, $view, $name);

        return $this;
    }

    public function post(string $url, string $view, ?string $name = null): self
    {
        self::$router->map('POST', $url, $view, $name);

        return $this;
    }

    /**
     * Responds to a route GET or POST
     *
     * @param  string $url
     * @param  string $view
     * @param  string|null $name
     * @return $this
     * @throws Exception
     */
    public function match(string $url, string $view, ?string $name = null): self
    {
        self::$router->map('POST|GET', $url, $view, $name);

        return $this;
    }

    public function url(string $nameRoute, array $params = [])
    {
        return self::$router->generate($nameRoute, $params);
    }

    public function run()
    {
        $match = self::$router->match();
        $view  = $match['target'] ?: 'e404';
        $params = $match['params'];

        $controller = 'App\\Controller\\' . $match['target']['controller'] . 'Controller';
        $controller = new $controller();
        call_user_func_array([$controller, $match['target']['action']], $params);

    }
}