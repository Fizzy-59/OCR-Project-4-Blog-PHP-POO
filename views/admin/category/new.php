<?php

use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\Model\Category;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Validators\CategoryValidator;

Auth::check();


$errors = [];
$item = new Category();

// If form complete
if (!empty($_POST))
{
    $pdo = Connection::getPDO();
    $table = new CategoryTable($pdo);

    // Valitron is a simple, minimal and elegant stand-alone validation library
    // https://github.com/vlucas/valitron
    $v = new CategoryValidator($_POST, $table);
    ObjectHelper::hydrate($item, $_POST, ['name', 'slug']);

    // Creating data in database
    if ($v->validate())
    {
        $table->create(
            [
                'name' => $item->getName(),
                'slug' => $item->getSlug()
            ]
        );
        header('Location: ' . $router->url('admin_categories') . '?created=1');
        exit();
    }
    else
    {
        $errors = $v->errors();
    }
}

$form = new Form($item, $errors);
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        La catégorie n'a pas pu être enrengistré, merci de corriger vos erreurs.
    </div>
<?php endif; ?>

<h1>Créer une catégorie</h1>

<?php require('_form.php') ?>