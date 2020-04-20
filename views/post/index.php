<?php

use App\Connection;
use App\Table\PostTable;

$title = 'Mon Blog';

$pdo = Connection::getPDO();

$table = new PostTable($pdo);
[$posts, $pagination] = $table->findPaginated();

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
    <?php echo $pagination->nextLink($link); ?>
    <?php echo $pagination->previousLink($link); ?>
</div>

