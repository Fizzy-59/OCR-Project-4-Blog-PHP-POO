<?php


namespace App\Controller;

use AltoRouter;
use App\Router;
use App\Connection;
use App\Table\PostTable;

class PostsController extends Controller
{

    public function index()
    {

        $router = new Router('../views/post');
//        require ('../public/index.php');

        $path = './views/post/index.php';
        $title = 'Mon Blog';

        $pdo = Connection::getPDO();

        $table = new PostTable($pdo);
        [$posts, $pagination] = $table->findPaginated();
        $link = '/';

        $this->render($posts, $pagination, $link);

    }

}