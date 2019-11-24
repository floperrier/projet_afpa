<?php

use App\Connection;
use App\HTML\Form;
use App\Table\PostTable;
use App\Validator;

$id = $params['id'];

$pdo = Connection::getPDO();
$postManager =  new PostTable($pdo);
$post = $postManager->find($id);
$success = null;
$errors = [];

if (!empty($_POST)) {
    Validator::lang('fr');
    $v = new Validator($_POST);
    $v->rule('required',['name', 'slug']);
    $v->rule('lengthBetween',['name', 'slug'],3,200);
    if ($v->validate()) {
        $post
            ->setName($_POST["name"])
            ->setSlug($_POST["slug"])
            ->setContent($_POST["content"])
            ->setCreatedAt($_POST['created_at']);
        $postManager->update($post);
        $success = "L'article a bien été modifié !";    
    } else {
        $errors = $v->errors();
    }
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
<form action="" method="post">
    <?= $form->input('name','Nom') ?>
    <?= $form->input('slug','URL') ?>
    <?= $form->textarea('content','Contenu') ?>
    <?= $form->input('created_at','Date de création') ?>

    <button class="btn btn-primary" type="submit">Modifier</button>
</form>