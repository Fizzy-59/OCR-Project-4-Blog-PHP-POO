<?php

namespace App;

use AltoRouter;
use App\Table\Exception\NotFoundException;
use Exception;

class Router
{

    private $viewPath;
    private $router;

    /**
     * Router constructor.
     * @param string $viewPath
     */
    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
    }

    public function get(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('GET', $url, $view, $name);

        return $this;
    }

    public function post(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('POST', $url, $view, $name);

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
        $this->router->map('POST|GET', $url, $view, $name);

        return $this;
    }

    public function url(string $nameRoute, array $params = [])
    {
        return $this->router->generate($nameRoute, $params);
    }

    public function run(): self
    {
        $match = $this->router->match();
        $view  = $match['target'] ?: 'e404';
        $params = $match['params'];
        $router = $this;

        // See if there is admin in the route, if yes change the default layout
        $isAdmin = strpos($view, 'admin/') !== false;
        $layout = $isAdmin ? 'admin/layouts/default' : 'layouts/default';

        try {
            ob_start();
            require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
            $content_for_layout = ob_get_clean();

            require $this->viewPath . DIRECTORY_SEPARATOR . $layout . '.php';
        } catch (NotFoundException $e) {
            header('Location: ' . $this->url('login') . '?forbidden=1');
            exit();
        }

        return $this;
    }
}