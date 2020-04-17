<?php

use App\Model\Post;

$title = 'Mon Blog';

$pdo = new PDO('mysql:dbname=blog_poo;host=127.0.0.1;port=8889', 'root', 'root',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

$currentPage = (int) ($_GET['page'] ?? 1);
if ($currentPage <= 0) { throw new Exception('Numéro de page invalide'); };

$countPost = (int) $pdo->query('SELECT COUNT(id) FROM post')->fetch(PDO::FETCH_NUM)[0];

// Count number of pages
$perPage = 12;
$pages = ceil($countPost / $perPage);
if ($currentPage > $pages) { throw new Exception('Cette page n\'éxiste pas'); };

// Recover from __ of __
$offset = $perPage * ($currentPage - 1);

$query = $pdo->query("SELECT * FROM post ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);

?>

<h1>Mon Blog</h1>

<div class="row">

    <?php foreach ($posts as $post): ?>
        <div class="col-md-3">
            <?= require 'card.php' ?>
        </div>
    <?php endforeach ?>

</div>

<!-- Paging-->
<div class="d-flex justify-content-between my-4">

    <?php if ($currentPage > 1): ?>
        <a href=" <?= $router->url('home') ?> ?page= <?= $currentPage - 1 ?>" class="btn btn-primary">
            &laquo; Page précèdente </a>
    <?php endif ?>

    <?php if ($currentPage < $pages): ?>
        <a href=" <?= $router->url('home') ?> ?page= <?= $currentPage + 1 ?>" class="btn btn-primary">
            Page suivante &raquo; </a>
    <?php endif ?>

</div>

