<?php

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

$currentPage = Url::getPositiveInt('page', 1);

$countCategory = (int)$pdo
    ->query('SELECT COUNT(category_id) FROM post_category WHERE category_id = ' . $category->getId() )
    ->fetch(PDO::FETCH_NUM)[0];

// Count number of pages
$perPage = 12;
$pages = ceil($countCategory / $perPage);
if ($currentPage > $pages) { throw new Exception('Cette page n\'éxiste pas'); };

// Recover from __ of __
$offset = $perPage * ($currentPage - 1);

$query = $pdo->query("
    SELECT p.*
    FROM post p
    JOIN post_category pc on pc.post_id = p.id
    WHERE pc.category_id = {$category->getId()}
    ORDER BY created_at DESC
    LIMIT $perPage OFFSET $offset
");
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);

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

    <?php if ($currentPage > 1): ?>
        <?php $l = $link;
        if ($currentPage > 2) $l = $link . '?page=' . ($currentPage -1);
        ?>
        <a href="<?= $l ?>" class="btn btn-primary"> &laquo; Page précèdente </a>
    <?php endif ?>

    <?php if ($currentPage < $pages): ?>
        <a href="<?= $link ?>?page=<?= $currentPage + 1 ?>" class="btn btn-primary">
            Page suivante &raquo; </a>
    <?php endif ?>

</div>
