<?php

use App\Connection;
use App\HTML\Form;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Validator\CategoryValidator;
use App\Auth;

Auth::check();

$id = $params['id'];

$pdo = Connection::getPDO();
$table =  new CategoryTable($pdo);
$category = $table->find($id);
$success = null;
$errors = [];
$fields = ['name','slug'];

if (!empty($_POST)) {
    $v = new CategoryValidator($_POST, $table, $category->getId());
    ObjectHelper::hydrate($category, $_POST, $fields);
    if ($v->validate()) {
        $table->update([
            "name" => $category->getName(),
            "slug" => $category->getSlug(),
        ],$category->getId());
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