<?php

use App\Connection;
use App\Table\PostTable;
use App\Validator;

$pdo = Connection::getPDO();

$postTable = new PostTable($pdo);
$post = $postTable->find($params['id']);
$success = false;

$errors = [];

if (!empty($_POST))
{
    // Valitron is a simple, minimal and elegant stand-alone validation library
    // https://github.com/vlucas/valitron
    Validator::lang('fr');
    $v = new Validator($_POST);
    $v->labels(array(
        'name' => 'Titre',
        'content' => 'Contenu'
    ));
    $v->rule('required', 'name');
    $v->rule('lengthBetween', 'name', 3, 200);

    $post->setName($_POST['name']);

    if ($v->validate())
    {
        $postTable->update($post);
        $success = true;
    }
    else
    {
        $errors = $v->errors();
    }
}

?>

<?php
if ($success): ?>
    <div class="alert alert-success">
        L'article a bien été modifié.
    </div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    L'article n'a pas pu être modifié, merci de corriger vos erreurs.
</div>
<?php endif; ?>




<h1>Editer l'article <?php echo htmlentities($post->getName()); ?></h1>

<form action="" method="post">

    <div class="form-group">
        <label for="name">Titre</label>
        <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid" ' : ''; ?>"
               name="name" value="<?php echo htmlentities($post->getName()); ?>" required>
        <?php if (isset($errors['name'])): ?>
        <div class="invalid-feedback">
            <?php echo implode('<br>', $errors['name']) ?>
        </div>
        <?php endif; ?>
    </div>
    <button class="btn btn-primary">Modifier</button>

</form>