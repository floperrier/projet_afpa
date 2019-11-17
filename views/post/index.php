<?php

use App\Connection;
use App\Helper\Text;
use App\Model\Post;
use App\URL;

$title = "Mon blog";
$pdo = Connection::getPDO();

// On recupere le numero de la page depuis l'URL
$currentPage = URL::getPositiveInt('page',1);

// On compte le nombre de pages et on vérifie que la page appellée existe
$perPage = 12;
$articlesNumber = (int)$pdo->query("SELECT count(id) FROM post")->fetch(PDO::FETCH_NUM)[0];
$pages = ceil($articlesNumber / $perPage);
if ($currentPage > $pages) {
    throw new Exception("Cette page n'existe pas");
}

// On récupère les articles à afficher
$offset = $perPage * ($currentPage - 1);
$posts = $pdo->query("SELECT * FROM post ORDER BY created_at DESC LIMIT 12 OFFSET $offset")->fetchAll(PDO::FETCH_CLASS,Post::class);

?>

<h1>Mon blog</h1>

<div class="row">
    <?php foreach ($posts as $post): ?>
        <?php require 'card.php' ?>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
    <?php if ($currentPage > 1): ?>
    <a class="btn btn-primary" href="<?= $router->url('home') ?>?page=<?= $currentPage - 1 ?>">Page précédente</a>
    <?php endif ?>
    <?php if ($currentPage < $pages): ?>
    <a class="btn btn-primary ml-auto" href="<?= $router->url('home') ?>?page=<?= $currentPage + 1 ?>">Page suivante</a>
    <?php endif ?>
</div>