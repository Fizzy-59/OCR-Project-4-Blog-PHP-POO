<?php

use App\Connection;
use App\HTML\Form;
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

    $v->rule('required', ['name', 'slug']);
    $v->rule('lengthBetween', ['name', 'slug'], 3, 200);

    $post
        ->setName($_POST['name'])
        ->setCreatedAt($_POST['created_at'])
        ->setSlug($_POST['slug'])
        ->setContent($_POST['content'])
    ;

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

$form = new Form($post, $errors);
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

<form action="" method="POST">
    <?php echo $form->input('name', 'Titre'); ?>
    <?php echo $form->input('slug', 'URL'); ?>
    <?php echo $form->textarea('content', 'Contenu')?>
    <?php echo $form->input('created_at', 'Date de création'); ?>

    <button class="btn btn-primary">Modifier</button>
</form>