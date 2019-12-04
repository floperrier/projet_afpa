<?php

use App\Connection;
use App\Model\User;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Table\UserTable;

$title = "Mon blog";
$pdo = Connection::getPDO();
$table = new PostTable($pdo);
$userTable = new UserTable($pdo);
$categoryTable = new CategoryTable($pdo);
$listeCategories = $categoryTable->all();
[$posts,$pagination] = $table->findPaginated(5);
$link = $router->url('home');
?>

<div class="row">
    <div class="col-9">
    <h1 class="text-center">Liste des articles</h1>
    <?php foreach ($posts as $post): ?>
        <?php $author = $userTable->find($post->getAuthorId()) ?>
        <?php require 'card.php' ?>
    <?php endforeach ?>
    </div>
    <div class="col-3">
        <h1>Catégories</h1>
        <ul class="list-group list-group-flush">
            <?php foreach ($listeCategories as $c): ?>
                <a href="<?= $router->url('category',['slug' => $c->getSlug(), 'id' => $c->getId()]) ?>" class="list-group-item"><?= $c->getName() ?></a>
            <?php endforeach ?>
        </ul>
    </div>
</div>

<div class="d-flex justify-content-between my-4">
<?= $pagination->previousLink($link) ?>
<?= $pagination->nextLink($link) ?>
</div>