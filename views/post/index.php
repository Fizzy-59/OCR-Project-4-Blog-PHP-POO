<?php

$title = 'Mon Blog';

$pdo = new PDO('mysql:dbname=blog_poo;host=127.0.0.1;port=8889', 'root', 'root',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

$query = $pdo->query('SELECT * FROM post ORDER BY created_at DESC LIMIT 12');
$posts = $query->fetchAll(PDO::FETCH_OBJ);

?>
<h1>Mon Blog</h1>

<div class="row">

    <?php foreach ($posts as $post): ?>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Titre</h5>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>


