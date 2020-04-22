<?php

use App\Auth;
use App\Connection;
use App\Table\PostTable;

Auth::check();

$title = "Administration";

$pdo = Connection::getPDO();
$link = $router->url('admin_posts');
[$posts, $pagination] = (new PostTable($pdo))->findPaginated();

?>


<?php if (isset($_GET['delete'])): ?>
    <div class="alert alert-success">
        L'enrengistrement a bien été supprimé
    </div>
<?php endif ?>



<table class="table">
    <thead>
    <th>#</th>
    <th>Titre</th>
    <th>Actions</th>
    </thead>
    <tbody>
    <?php foreach ($posts as $post): ?>
    <tr>
        <td>#<?php echo $post->getId(); ?></td>
        <td>
            <a href="<?php echo $router->url('admin_post', ['id' => $post->getId()]); ?>">
            <?php echo htmlentities($post->getName()); ?>
            </a>
        </td>
        <td>
            <a href="<?php echo $router->url('admin_post', ['id' => $post->getId()]); ?>" class="btn btn-primary">
                Editer
            </a>
            <form action="<?php echo $router->url('admin_post_delete', ['id' => $post->getId()]); ?>" method="POST"
                onsubmit="return confirm('Voulez vous vraiment effectuer cette action ?')" style="display: inline">

                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Paging-->
<div class="d-flex justify-content-between my-4">
    <?php echo $pagination->nextLink($link); ?>
    <?php echo $pagination->previousLink($link); ?>
</div>
