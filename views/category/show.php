<?php

use App\PaginatedQuery;
use App\Url;
use App\Connection;
use App\Model\Category;
use App\Model\Post;

$id = (int) $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();

// Category
$query = $pdo->prepare('SELECT * FROM category WHERE id = :id');
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Category::class);
/** @var category|false */
$category = $query->fetch();

if($category === false)
{
    throw new Exception('Aucune catégorie ne correspond à cet ID');
}

if ($category->getSlug() !== $slug)
{
    $url = $router->url('post', ['slug' => $category->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
};

$title = "Catégorie : {$category->getName()}";

$paginatedQuery = new PaginatedQuery
(
        "SELECT p.*
    FROM post p
    JOIN post_category pc on pc.post_id = p.id
    WHERE pc.category_id = {$category->getId()}
    ORDER BY created_at DESC",
    "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$category->getId()}"
);

/** @var Post[] */
$posts = $paginatedQuery->getItems(Post::class);

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
