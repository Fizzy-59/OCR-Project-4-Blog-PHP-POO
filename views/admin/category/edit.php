<?php

use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Validators\CategoryValidator;

Auth::check();

$pdo = Connection::getPDO();

$table = new CategoryTable($pdo);
$item = $table->find($params['id']);

$success = false;
$errors = [];
$fields = ['name', 'slug'];

if (!empty($_POST))
{
    // Valitron is a simple, minimal and elegant stand-alone validation library
    // https://github.com/vlucas/valitron
    $v = new CategoryValidator($_POST, $table, $item->getId());
    ObjectHelper::hydrate($item, $_POST, $fields);

    if ($v->validate())
    {
        $table->update(
            [
                'name' => $item->getName(),
                'slug' => $item->getSlug(),
            ], $item->getId());

        $success = true;
    }
    else
    {
        $errors = $v->errors();
    }
}

$form = new Form($item, $errors);
?>

<?php
if ($success): ?>
    <div class="alert alert-success">
        La catégorie a bien été modifié.
    </div>
<?php endif; ?>

<?php
    if (isset($_GET['created'])): ?>
    <div class="alert alert-success">
        La catégorie a bien été créé.
    </div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    La catégorie n'a pas pu être modifié, merci de corriger vos erreurs.
</div>
<?php endif; ?>

<h1>Editer la catégorie <?php echo htmlentities($item->getName()); ?></h1>

<?php require('_form.php') ?>