<?php

use App\Connection;
use App\Table\PostTable;

$pdo = Connection::getPDO();
$postManager =  new PostTable($pdo);
[$posts,$pagination] = $postManager->findPaginated();
$link = $router->url('admin_posts');
?>

<?php if (isset($_GET["delete"])): ?>
    <div class="alert alert-success">
        L'article a bien été supprimé !
    </div>
<?php endif ?>

<h1>Articles</h1>
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Titre</th>
            <th>Edition</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($posts as $post): ?>
        <tr>
            <td><?= $post->getId() ?></td>
            <td><a href="<?= $router->url('admin_post',["id" => $post->getId(), "slug" => $post->getSlug()]) ?>"><?= $post->getName() ?></a></td>
            <td>
                <a name="" id="" class="btn btn-primary" href="<?= $router->url('admin_post',["id" => $post->getId()]) ?>" role="button">Modifier</a>
                <form method="post" action="<?= $router->url('admin_post_delete',["id" => $post->getId()]) ?>" onsubmit="return confirm('Etes-vous sûr de vouloir supprimer l\'article ?')" style="display:inline">
                    <button class="btn btn-danger" type="submit" >Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
<div class="d-flex justify-content-between my-4">
<?= $pagination->previousLink($link) ?>
<?= $pagination->nextLink($link) ?>
</div>