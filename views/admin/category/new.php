<?php

use App\Table\Connection;
use App\HTML\Form;
use App\Model\Category;
use App\Helper\ObjectHelper;
use App\Validator\CategoryValidator;
use App\Security\Auth;
use App\Table\CategoryTable;

Auth::check();

$errors = [];
$category = new Category();

if (!empty($_POST)) {
    $pdo = Connection::getPDO();
    $categoryTable = new CategoryTable($pdo);
    $v = new CategoryValidator($_POST, $categoryTable);
    ObjectHelper::hydrate($category,$_POST,['name','slug']);

    if ($v->validate()) {
        $id = $categoryTable->create([
            'name' => $category->getName(),
            'slug' => $category->getSlug()
        ]);
        header('Location: ' . $router->url('admin_categories') . '?created=1');
        exit();
    } else {
        $errors = $v->errors();
    }
}
$form = new Form($category,$errors);
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        Des erreurs sont présentes, veuillez les corriger avant de continuer
    </div>
<?php endif ?>

<h1>Nouvel article</h1>
<?php require('_form.php') ?>