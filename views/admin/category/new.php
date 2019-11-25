<?php

use App\Connection;
use App\HTML\Form;
use App\Model\Category;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Validator\CategoryValidator;

$errors = [];
$category = new Category();

if (!empty($_POST)) {
    $pdo = Connection::getPDO();
    ObjectHelper::hydrate($category,$_POST,['name','slug']);
    $categoryTable = new CategoryTable($pdo);
    $v = new CategoryValidator($_POST, $categoryTable);
    if ($v->validate()) {
        $categoryTable->create($category);
        header('Location: ' . $router->url('admin_category',['id' => $category->getId()]) . '?created=1');
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