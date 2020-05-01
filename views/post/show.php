<?php

use App\Connection;
use App\Table\CategoryTable;
use App\Table\PostTable;

$id = (int) $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();

$post = (new PostTable($pdo))->find($id);
(new CategoryTable($pdo))->hydratePost([$post]);

if ($post->getSlug() !== $slug)
{
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
};

?>

<!--Title-->
<h1 class="card-title"><?= htmlentities($post->getName() )?> </h1>

<!--Date-->
<p class="text-muted"> <?= $post->getcreatedAt()->format('d F y') ?></p>

<!-- Lis of categories -->
<?php foreach ($post->getCategories() as $k => $category):
    if ($k > 0):
        echo ',';
    endif;
    $categoryUrl = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()])
    ?>
<a href="<?= $categoryUrl ?>"> <?= htmlentities($category->getName()); ?> </a>
<?php endforeach ?>

<?php if ($post->getImage()): ?>
    <p>
        <img src="<?php echo $post->getImageURL('large'); ?>" alt=""  style="width: 100%">
    </p>
<?php endif; ?>

<!--Content-->
<p> <?= $post->getFormattedContent() ?></p>
