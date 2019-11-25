<?php

use App\Connection;
use App\HTML\Form;
use App\Model\Post;
use App\ObjectHelper;
use App\Table\PostTable;
use App\Validator\PostValidator;

$errors = [];
$post = new Post();

if (!empty($_POST)) {
    $pdo = Connection::getPDO();
    ObjectHelper::hydrate($post,$_POST,['name','slug','content','created_at']);
    $postTable = new PostTable($pdo);
    $v = new PostValidator($_POST, $postTable);
    if ($v->validate()) {
        $postTable->create($post);
        header('Location: ' . $router->url('admin_post',['id' => $post->getId()]) . '?created=1');
        exit();
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

<h1>Nouvel article</h1>
<?php require('_form.php') ?>