<?php

use App\Connection;
use App\HTML\Form;
use App\Model\Post;
use App\ObjectHelper;
use App\Table\PostTable;
use App\Validator\PostValidator;
use App\Auth;

Auth::check();

$errors = [];
$post = new Post();

if (!empty($_POST)) {
    $pdo = Connection::getPDO();
    ObjectHelper::hydrate($post,$_POST,['name','slug','content','created_at']);
    $postTable = new PostTable($pdo);
    $v = new PostValidator($_POST, $postTable);
    if ($v->validate()) {
        $id = $postTable->create([
            "name" => $post->getName(),
            "slug" => $post->getSlug(),
            "content" => $post->getContent(),
            "created_at" => $post->getCreatedAt()->format("Y-m-d H:i:s")
        ]);
        $post->setId($id);
        header('Location: ' . $router->url('admin_posts') . '?created=1');
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