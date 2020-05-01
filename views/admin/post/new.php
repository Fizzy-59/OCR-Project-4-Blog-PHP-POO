<?php

use App\Attachment\PostAttachment;
use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\Model\Post;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Validators\PostValidator;

Auth::check();


$errors = [];
$post = new Post();
$pdo = Connection::getPDO();
$categoryTable = new CategoryTable($pdo);
$categories = $categoryTable->list();
$post->setCreatedAt(date('Y-m-d H:i:s'));


if (!empty($_POST))
{
    $postTable = new PostTable($pdo);
    $data = array_merge($_POST, $_FILES);

    // Valitron is a simple, minimal and elegant stand-alone validation library
    // https://github.com/vlucas/valitron
    $v = new PostValidator($data, $postTable, $post->getId(), $categories);
    ObjectHelper::hydrate($post, $data, ['name', 'content', 'slug', 'created_at', 'image']);

    if ($v->validate())
    {
        PostAttachment::upload($post);
        $postTable->createPost($post);
        $postTable->attachCategories($post->getId(), $_POST['categories_ids']);

        header('Location: ' . $router->url('admin_post', ['id' => $post->getId()]) . '?created=1');
        exit();
    }
    else
    {
        $errors = $v->errors();
    }
}

$form = new Form($post, $errors);
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        L'article n'a pas pu être enrengistré, merci de corriger vos erreurs.
    </div>
<?php endif; ?>

<h1>Créer un article</h1>

<?php require('_form.php') ?>