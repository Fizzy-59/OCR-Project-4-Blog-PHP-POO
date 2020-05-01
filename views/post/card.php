 <?php
$categories = array_map(function ($category) use($router) {
    $url = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]);
    return <<<HTML
      <a href="{$url}">{$category->getName()}</a>
HTML;
}, $post->getCategories());


?>

<div class="card mb-3">
    <?php if ($post->getImage()): ?>
        <img src="<?php echo $post->getImageURL('small'); ?>" alt="" class="card-img-top">
    <?php endif; ?>

    <div class="card-body">

        <!--Title-->
        <h5 class="card-title"><?= htmlentities($post->getName() )?> </h5>

        <!--Date-->
        <p class="text-muted">
            <?= $post->getcreatedAt()->format('d F y') ?>
            <!-- Lis of categories -->
        <?php if (!empty($post->getCategories())): ?>
            ::
        <?php echo implode(',', $categories); ?>
            <?php endif; ?>
        </p>

        <!--Abstract-->
        <p> <?= $post->getExcerpt() ?></p>

        <!--Button-->
        <p>
            <a href="
            <?= $router->url( 'post', ['id' => $post->getId(), 'slug' => $post->getSlug()] ) ?>"
               class="btn btn-primary">Voir plus
            </a>
        </p>

    </div>
</div>
