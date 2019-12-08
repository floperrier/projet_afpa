<?php

use App\Table\Connection;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Table\UserTable;

$id = $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$categoryTable = new CategoryTable($pdo);
$postTable = new PostTable($pdo);
$userTable = new UserTable($pdo);


$category = $categoryTable->find($id);

// On vérifie que le slug correspond, sinon on redirige vers la bonne url
if ($slug !== $category->getSlug()) {
    http_response_code(301);
    header('Location: ' . $router->url('category',['slug' => $category->getSlug(), 'id' => $category->getId()]));
}

[$posts,$pagination] = $postTable->findPaginatedForCategory($category->getId());
?>

<h1 class="text-center">Catégorie "<?= htmlentities($category->getName()) ?>"</h1>
<hr>
<div class="row">
    <div class="col-10 mx-auto">
    <?php foreach ($posts as $post): ?>      
        <?php $author = $userTable->find($post->getAuthorId()) ?>
        <?php require dirname(__DIR__) . '/post/card.php' ?>
    <?php endforeach ?>

    </div>
</div>

<div class="d-flex justify-content-between my-4">
<?= $pagination->previousLink($router->url('category',['id' => $category->getId(), 'slug' => $category->getSlug()])) ?>
<?= $pagination->nextLink($router->url('category',['id' => $category->getId(), 'slug' => $category->getSlug()])) ?>
</div>