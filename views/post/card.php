<div class="card mb-3">
    <div class="card-body">

        <!--Title-->
        <h5 class="card-title"><?= htmlentities($post->getName() )?> </h5>

        <!--Date-->
        <p class="text-muted"> <?= $post->getcreatedAt()->format('d F y') ?></p>

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
