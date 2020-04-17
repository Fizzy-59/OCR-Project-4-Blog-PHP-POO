<?php

use App\Connection;
use App\Model\Category;
use App\Model\Post;

$id = (int) $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();

// Post
$query = $pdo->prepare('SELECT * FROM post WHERE id = :id');
$query->execute(['id' => $id]);

$query->setFetchMode(PDO::FETCH_CLASS, Post::class);

/** @var Post|false */
$post = $query->fetch();

if($post === false)
{
    throw new Exception('Aucun article ne correspond à cet ID');
}

if ($post->getSlug() !== $slug)
{
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
};

// Category
$query = $pdo->prepare
(
    'SELECT c.id, c.slug, c.name 
    FROM post_category pc 
    JOIN category c on pc.category_id = c.id 
    WHERE pc.post_id = :id'
);

$query->execute(['id' => $post->getId()]);

$query->setFetchMode(PDO::FETCH_CLASS, Category::class);

/** @var Category[] */
$categories = $query->fetchAll();

?>

<!--Title-->
<h1 class="card-title"><?= htmlentities($post->getName() )?> </h1>

<!--Date-->
<p class="text-muted"> <?= $post->getcreatedAt()->format('d F y') ?></p>

<!-- Lis of categories -->
<?php foreach ($categories as $category):
    $categoryUrl = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()])
    ?>
<a href="<?= $categoryUrl ?>"> <?= htmlentities($category->getName()); ?> </a>
<?php endforeach ?>

<!--Content-->
<p> <?= $post->getFormattedContent() ?></p>
