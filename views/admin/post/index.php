<?php

use App\Connection;
use App\Table\PostTable;
use App\Auth;

Auth::check();

$pdo = Connection::getPDO();
$postManager =  new PostTable($pdo);
[$posts,$pagination] = $postManager->findPaginated();
$link = $router->url('admin_posts');
?>

<?php if (isset($_GET["deleted"])): ?>
    <div class="alert alert-success">
        L'article a bien été supprimé !
    </div>
<?php endif ?>

<?php if (isset($_GET["created"])): ?>
    <div class="alert alert-success">
        L'article a bien été créé !
    </div>
<?php endif ?>

<div class="d-flex justify-content-between mb-4 align-items-center">
<h1>Articles</h1>
<a class="btn btn-success mr-4" href="<?= $router->url('admin_post_new') ?>">Nouvel article</a>
</div>
<table class="table text-center">
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
                <a name="" id="" class="btn btn-primary" href="<?= $router->url('admin_post',["id" => $post->getId()]) ?>" role="button">Editer</a>
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