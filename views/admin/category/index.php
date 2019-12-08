<?php

use App\Table\Connection;
use App\Table\CategoryTable;
use App\Security\Auth;

Auth::check();

$pdo = Connection::getPDO();
$categoryTable =  new CategoryTable($pdo);
$categories = $categoryTable->all();
$link = $router->url('admin_categories');
?>

<?php if (isset($_GET["deleted"])): ?>
    <div class="alert alert-success">
        La catégorie a bien été supprimé !
    </div>
<?php endif ?>

<div class="d-flex justify-content-between mb-4 align-items-center">
<h1>Gestion des catégories</h1>
</div>
<table class="table table-hover table-sm text-center">
    <thead>
        <tr>
            <th class="font-weight-bold">#</th>
            <th class="font-weight-bold">Nom</th>
            <th class=""><a class="btn btn-success" href="<?= $router->url('admin_category_new') ?>"><i class="fas fa-plus"></i>  Nouvelle catégorie</a></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($categories as $category): ?>
        <tr>
            <td class="align-middle"><?= $category->getId() ?></td>
            <td class="align-middle"><a href="<?= $router->url('admin_category',["id" => $category->getId(), "slug" => $category->getSlug()]) ?>"><?= $category->getName() ?></a></td>
            <td class="">
                <a name="" id="" class="btn btn-primary btn-sm" href="<?= $router->url('admin_category',["id" => $category->getId()]) ?>" role="button">Modifier</a>
                <form method="POST" action="<?= $router->url('admin_category_delete',["id" => $category->getId()]) ?>" onsubmit="return confirm('Etes-vous sûr de vouloir supprimer la catégorie ?')" style="display:inline">
                    <button class="btn btn-danger btn-sm" type="submit" >Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>