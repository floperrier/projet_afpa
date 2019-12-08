<?php

use App\Table\Connection;
use App\Table\PostTable;
use App\Security\Auth;

Auth::check();

$pdo = Connection::getPDO();
$postManager =  new PostTable($pdo);
[$posts,$pagination] = $postManager->findPaginated(12);
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
<h1>Gestion des articles</h1>
</div>
<table class="table table-hover table-sm text-center">
    <thead>
        <tr>
            <th class="font-weight-bold">#</th>
            <th class="font-weight-bold text-uppercase">Titre</th>
            <th><a class="btn btn-success" href="<?= $router->url('admin_post_new') ?>"><i class="fas fa-plus"></i> Nouvel article</a></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($posts as $post): ?>
        <tr>
            <td class="align-middle"><?= $post->getId() ?></td>
            <td class="align-middle"><a href="<?= $router->url('admin_post',["id" => $post->getId(), "slug" => $post->getSlug()]) ?>"><?= $post->getName() ?></a></td>
            <td>
                <a name="" id="" class="btn btn-primary m-1 btn-sm" href="<?= $router->url('admin_post',["id" => $post->getId()]) ?>" role="button">Editer</a>
                <form method="post" action="<?= $router->url('admin_post_delete',["id" => $post->getId()]) ?>" onsubmit="return confirm('Etes-vous sûr de vouloir supprimer l\'article ?')" style="display:inline">
                    <button class="btn btn-danger m-1 btn-sm" type="submit" >Supprimer</button>
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