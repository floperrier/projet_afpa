<?php

use App\Connection;
use App\HTML\Form;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Validator\CategoryValidator;

$id = $params['id'];

$pdo = Connection::getPDO();
$categoryTable =  new CategoryTable($pdo);
$category = $categoryTable->find($id);
$success = null;
$errors = [];

if (!empty($_POST)) {
    $v = new CategoryValidator($_POST, $categoryTable, $category->getId());
    ObjectHelper::hydrate($category,$_POST,['name','slug']);
    if ($v->validate()) {
        $categoryTable->update($category);
        $success = "La catégorie a bien été modifié !";    
    } else {
        $errors = $v->errors();
    }
}

if (isset($_GET['created'])) {
    $success = "La catégorie a bien été créé !";
}
$form = new Form($category,$errors);
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        Des erreurs sont présentes, veuillez les corriger avant de continuer
    </div>
<?php endif ?>

<?php if ($success): ?>
    <div class="alert alert-success">
        <?= $success ?>
    </div>
<?php endif ?>

<h1>Edition de la catégorie <?= $category->getName() ?></h1>
<?php require('_form.php') ?>