<?php

use App\Connection;
use App\Model\Category;
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

// Retrieving the categories and the article associated with them
$postsById = [];
foreach ($posts as $post)
{
    $postsById[$post->getId()] = $post;
}
$categories = $pdo->query
(
        'SELECT c.*, pc.post_id 
    FROM post_category pc 
    JOIN category c on c.id = pc.category_id 
    WHERE pc.post_id IN(' . implode(',', array_keys($postsById)) . ') 
')->fetchAll(PDO::FETCH_CLASS, Category::class);

foreach ($categories as $category)
{
    $postsById[$category->getPostId()]->addCategory($category);
}

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

