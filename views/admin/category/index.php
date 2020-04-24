<?php

use App\Auth;
use App\Connection;
use App\Table\CategoryTable;

Auth::check();

$title = "Gestion des catégories";

$router->layout = "admin/layouts/default";

$pdo = Connection::getPDO();
$link = $router->url('admin_categories');
$items = (new CategoryTable($pdo))->all();$item
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
    <th>
        <a href="<?php echo $router->url('admin_category_new'); ?>" class="btn btn-primary">Nouveau</a>
    </th>
    </thead>
    <tbody>
    <?php foreach ($items as $item): ?>
    <tr>
        <td>#<?php echo $item->getId(); ?></td>
        <td>
            <a href="<?php echo $router->url('admin_category', ['id' => $item->getId()]); ?>">
            <?php echo htmlentities($item->getName()); ?>
            </a>
        </td>
        <td>
            <a href="<?php echo $router->url('admin_category', ['id' => $item->getId()]); ?>" class="btn btn-primary">
                Editer
            </a>
            <form action="<?php echo $router->url('admin_category_delete', ['id' => $item->getId()]); ?>" method="POST"
                onsubmit="return confirm('Voulez vous vraiment effectuer cette action ?')" style="display: inline">

                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

