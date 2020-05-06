<?php


namespace App\Controller;


use App\Router;

class Controller
{
    protected $viewPath;
    protected $router;

    public function __construct()
    {
        $this->router = Router::getInstance();
    }

    public function render($posts, $pagination, $link)
    {
        ob_start();
        require('../views/post/index.php');
        $content_for_layout = ob_get_clean();
        require('../views/layouts/default.php');
        return;
    }



}