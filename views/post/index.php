<?php

use App\Connection;
use App\Helper\Text;
use App\Model\Post;
use App\PaginatedQuery;
use App\URL;

$title = "Mon blog";
$pdo = Connection::getPDO();

$paginatedQuery = new PaginatedQuery(
    "SELECT count(id) FROM post",
    "SELECT * FROM post ORDER BY created_at DESC",
    Post::class
);
$posts = $paginatedQuery->getItems();
?>

<h1>Mon blog</h1>

<div class="row">
    <?php foreach ($posts as $post): ?>
        <?php require 'card.php' ?>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
<?= $paginatedQuery->previousLink($router->url('home')) ?>
<?= $paginatedQuery->nextLink($router->url('home')) ?>

</div>