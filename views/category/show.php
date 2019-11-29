<?php

use App\Connection;
use App\Model\{Category,Post};
use App\PaginatedQuery;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\URL;

$id = $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$category = (new CategoryTable($pdo))->find($id);

// On vérifie que le slug correspond, sinon on redirige vers la bonne url
if ($slug !== $category->getSlug()) {
    http_response_code(301);
    header('Location: ' . $router->url('category',['slug' => $category->getSlug(), 'id' => $category->getId()]));
}

[$posts,$pagination] = (new PostTable($pdo))->findPaginatedForCategory($category->getId());
$link = $router->url('category',['id' => $category->getId(), 'slug' => $category->getSlug()]);
?>

<h1 class="text-center">Catégorie "<?= htmlentities($category->getName()) ?>"</h1>
<hr>
<div class="row">
    <div class="col-10 mx-auto">
    <?php foreach ($posts as $post): ?>
        <?php require dirname(__DIR__) . '/post/card.php' ?>
    <?php endforeach ?>

    </div>
</div>

<div class="d-flex justify-content-between my-4">
<?= $pagination->previousLink($link) ?>
<?= $pagination->nextLink($link) ?>
</div>