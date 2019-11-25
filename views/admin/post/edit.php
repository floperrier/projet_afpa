<?php

use App\Connection;
use App\HTML\Form;
use App\ObjectOperations;
use App\Table\PostTable;
use App\Validator\PostValidator;

$id = $params['id'];

$pdo = Connection::getPDO();
$postTable =  new PostTable($pdo);
$post = $postTable->find($id);
$success = null;
$errors = [];

if (!empty($_POST)) {
    $v = new PostValidator($_POST, $postTable, $post->getId());
    ObjectOperations::hydrate($post,$_POST,['name','slug','content','created_at']);
    dd($post);
    /* $post
        ->setName($_POST["name"])
        ->setSlug($_POST["slug"])
        ->setContent($_POST["content"])
        ->setCreatedAt($_POST['created_at']); */
    if ($v->validate()) {
        $postTable->update($post);
        $success = "L'article a bien été modifié !";    
    } else {
        $errors = $v->errors();
    }
}

if (isset($_GET['created'])) {
    $success = "L'article a bien été créé !";
}
$form = new Form($post,$errors);
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

<h1>Edition de l'article <?= $post->getName() ?></h1>
<?php require('_form.php') ?>