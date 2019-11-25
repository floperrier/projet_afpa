<?php

use App\Connection;
use App\Table\CategoryTable;

$pdo = Connection::getPDO();
$categoryTable =  new CategoryTable($pdo);
[$categories,$pagination] = $categoryTable->findPaginated();
$link = $router->url('admin_categories');
?>

<?php if (isset($_GET["delete"])): ?>
    <div class="alert alert-success">
        La catégorie a bien été supprimé !
    </div>
<?php endif ?>

<div class="d-flex justify-content-between mb-4 align-items-center">
<h1>Catégories</h1>
<a class="btn btn-success mr-4" href="<?= $router->url('admin_category_new') ?>">Nouvelle catégorie</a>
</div>
<table class="table text-center">
    <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Edition</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($categories as $category): ?>
        <tr>
            <td><?= $category->getId() ?></td>
            <td><a href="<?= $router->url('admin_category',["id" => $category->getId(), "slug" => $category->getSlug()]) ?>"><?= $category->getName() ?></a></td>
            <td>
                <a name="" id="" class="btn btn-primary" href="<?= $router->url('admin_category',["id" => $category->getId()]) ?>" role="button">Modifier</a>
                <form method="POST" action="<?= $router->url('admin_category_delete',["id" => $category->getId()]) ?>" onsubmit="return confirm('Etes-vous sûr de vouloir supprimer la catégorie ?')" style="display:inline">
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