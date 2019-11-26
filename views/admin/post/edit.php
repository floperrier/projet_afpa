<?php

use App\Connection;
use App\HTML\Form;
use App\Table\PostTable;
use App\Validator\PostValidator;
use App\Auth;
use App\ObjectHelper;
use App\Table\CategoryTable;

Auth::check();

$id = $params['id'];

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$categoryTable = new CategoryTable($pdo);
$categories = $categoryTable->list();
$post = $postTable->find($id);
$categoryTable->hydratePosts([$post]);
$success = null;
$errors = [];

if (!empty($_POST)) {
    $v = new PostValidator($_POST, $postTable, $post->getId());
    ObjectHelper::hydrate($post,$_POST,['name','slug','content','created_at']);
    if ($v->validate()) {
        $postTable->update([
            "name" => $post->getName(),
            "slug" => $post->getSlug(),
            "content" => $post->getContent(),
            "created_at" => $post->getCreatedAt()->format("Y-m-d H:i:s")
        ],$post->getId());
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