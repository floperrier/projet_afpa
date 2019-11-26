<?php

use App\Connection;
use App\HTML\Form;
use App\Model\Category;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Validator\CategoryValidator;
use App\Auth;

Auth::check();

$errors = [];
$category = new Category();

if (!empty($_POST)) {
    $pdo = Connection::getPDO();
    $categoryTable = new CategoryTable($pdo);

    ObjectHelper::hydrate($category,$_POST,['name','slug']);
    $v = new CategoryValidator($_POST, $categoryTable);
    if ($v->validate()) {
        $id = $categoryTable->create([
            'name' => $category->getName(),
            'slug' => $category->getSlug()
        ]);
        $category->setId($id);
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