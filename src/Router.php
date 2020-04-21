<?php

namespace App;

use AltoRouter;

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

    public function url(string $nameRoute, array $params = [])
    {
        return $this->router->generate($nameRoute, $params);
    }

    public function run(): self
    {
        $match = $this->router->match();
        $view  = $match['target'];
        $params = $match['params'];
        $router = $this;

        ob_start();
        require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
        $content_for_layout = ob_get_clean();

        require $this->viewPath . DIRECTORY_SEPARATOR . 'layouts/default.php';

        return $this;
    }
}