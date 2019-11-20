<?php

use App\Connection;
use App\Helper\Text;
use App\Model\Category;
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
foreach ($posts as $post) {
    $postsById[$post->getId()] = $post;
}
$categories = $pdo
    ->query("SELECT c.*, pc.post_id FROM post_category pc
            JOIN category c ON pc.category_id = c.id
            WHERE pc.post_id IN (" . implode(',',array_keys($postsById)) . ")")
    ->fetchAll(PDO::FETCH_CLASS,Category::class);

foreach($categories as $category) {
    $postsById[$category->getPostId()]->addCategory($category);
}
$link = $router->url('home');
?>

<h1>Mon blog</h1>

<div class="row">
    <?php foreach ($posts as $post): ?>
        <?php require 'card.php' ?>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
<?= $paginatedQuery->previousLink($link) ?>
<?= $paginatedQuery->nextLink($link) ?>
</div>