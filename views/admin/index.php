<?php

use App\Connection;
use App\Table\PostTable;

$pdo = Connection::getPDO();
$postManager =  new PostTable($pdo);
[$posts,$pagination] = $postManager->findPaginated();
$link = $router->url("admin");
?>

<h1>Articles</h1>
<table class="table">
    <thead>
        <tr>
            <th>Titre</th>
            <th>Edition</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($posts as $post): ?>
        <tr>
            <td><a href="<?= $router->url('post',["id" => $post->getId(), "slug" => $post->getSlug()]) ?>"><?= $post->getName() ?></a></td>
            <td>
                <a name="" id="" class="btn btn-primary" href="<?= $router->url('admin_post',[$post->getId()]) ?>" role="button">Modifier</a>
                <a name="" id="" class="btn btn-primary" href="<?= $router->url('admin_post',[$post->getId()]) ?>" role="button">Supprimer</a>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
<div class="d-flex justify-content-between my-4">
<?= $pagination->previousLink($link) ?>
<?= $pagination->nextLink($link) ?>
</div>