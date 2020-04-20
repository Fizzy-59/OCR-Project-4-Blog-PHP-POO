<?php

use App\Table\CategoryTable;
use App\Connection;
use App\Table\PostTable;


$id = (int) $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();

$category = (new CategoryTable($pdo))->find($id);


if ($category->getSlug() !== $slug)
{
    $url = $router->url('post', ['slug' => $category->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
};

$title = "Catégorie : {$category->getName()}";

[$posts, $paginatedQuery] = (new PostTable($pdo))->findPaginatedForCategory($category->getId());

$link = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]);

?>

<h1> <?php echo htmlentities($title);  ?></h1>

<div class="row">
    <?php foreach ($posts as $post): ?>
        <div class="col-md-3">
            <?php require dirname(__DIR__) . '/post/card.php' ?>
        </div>
    <?php endforeach ?>
</div>

<!-- Paging-->
<div class="d-flex justify-content-between my-4">

    <?php echo $paginatedQuery->previousLink($link); ?>
    <?php echo $paginatedQuery->nextLink($link); ?>

</div>
