<?php

use App\Connection;
use App\Model\Post;
use App\PaginatedQuery;
use App\Url;

$title = 'Mon Blog';

$pdo = Connection::getPDO();

$paginatedQuery = new PaginatedQuery
(
    "SELECT * FROM post ORDER BY created_at DESC",
    "SELECT COUNT(id) FROM post"
);

$posts = $paginatedQuery->getItems(Post::class);
$link = $router->url('home')

?>

<h1>Mon Blog</h1>

<div class="row">

    <?php foreach ($posts as $post): ?>
        <div class="col-md-3">
            <?php require 'card.php' ?>
        </div>
    <?php endforeach ?>

</div>

<!-- Paging-->
<div class="d-flex justify-content-between my-4">
    <?php echo $paginatedQuery->nextLink($link); ?>
    <?php echo $paginatedQuery->previousLink($link); ?>
</div>

