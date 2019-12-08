<?php

use App\Table\Connection;
use App\HTML\Form;
use App\Model\Post;
use App\Helper\ObjectHelper;
use App\Table\PostTable;
use App\Validator\PostValidator;
use App\Security\Auth;
use App\Table\CategoryTable;

Auth::check();

$errors = [];
$post = new Post();

$pdo = Connection::getPDO();
$categoryTable = new CategoryTable($pdo);
$categories = $categoryTable->list();

if (!empty($_POST)) {
    $postTable = new PostTable($pdo);
    $v = new PostValidator($_POST, $postTable, $post->getId(), $categories);
    ObjectHelper::hydrate($post,$_POST,['name','slug','content','created_at']);
    
    if ($v->validate()) {
        $pdo->beginTransaction();
        // $postTable->createPost($post, $_SESSION['auth']);
        $idPost = $postTable->create([
            "name" => $post->getName(),
            "slug" => $post->getSlug(),
            "content" => $post->getContent(),
            "created_at" => $post->getCreatedAt()->format("Y-m-d H:i:s"),
            "author_id" => $_SESSION['auth']
        ]);
        $postTable->attachCategories($idPost, $_POST['categories_ids']);
        $pdo->commit();
        header('Location: ' . $router->url('admin_posts') . '?created=1');
        exit();
    } else {
        $errors = (array)$v->errors();
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