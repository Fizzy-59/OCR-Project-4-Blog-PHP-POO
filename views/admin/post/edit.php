<?php

use App\Attachment\PostAttachment;
use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Validators\PostValidator;

Auth::check();

$pdo = Connection::getPDO();

$postTable = new PostTable($pdo);
$categoryTable = new CategoryTable($pdo);
$categories = $categoryTable->list();

$post = $postTable->find($params['id']);
$categoryTable->hydratePost([$post]);
$success = false;

$errors = [];

if (!empty($_POST))
{
    $data = array_merge($_POST, $_FILES);

    // Valitron is a simple, minimal and elegant stand-alone validation library
    // https://github.com/vlucas/valitron
    $v = new PostValidator($data, $postTable, $post->getId(), $categories);
    ObjectHelper::hydrate($post, $data, ['name', 'content', 'slug', 'created_at', 'image']);

    if ($v->validate())
    {
        PostAttachment::upload($post);
        $postTable->updatePost($post);
        $postTable->attachCategories($post->getId(), $_POST['categories_ids']);

        $categoryTable->hydratePost([$post]);
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

<?php
    if (isset($_GET['created'])): ?>
    <div class="alert alert-success">
        L'article a bien été créé.
    </div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    L'article n'a pas pu être modifié, merci de corriger vos erreurs.
</div>
<?php endif; ?>

<h1>Editer l'article <?php echo htmlentities($post->getName()); ?></h1>

<?php require('_form.php') ?>