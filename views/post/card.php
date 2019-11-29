<?php
$categories = array_map(function ($category) use ($router) {
    $url = $router->url('category',['slug' => $category->getSlug(), 'id' => $category->getId()]);
    $categoryName = htmlentities($category->getName());
    return "<a class='badge badge-secondary p-2 mr-2' href='{$url}'>{$categoryName}</a>";
},$post->getCategories());
?>
<div class="col mt-1 mb-3 mx-auto">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">
                <a class="decoration-none text-dark" href="<?= $router->url('post',["id" => $post->getId(), "slug" => $post->getSlug()]) ?>">
                <?= htmlentities($post->getName()) ?></a>
            </h3>
            <?= implode('',$categories) ?>
            <hr>
            <p class="card-text"><?= $post->getExcerpt() ?></p>
            <div class="d-flex justify-content-between align-items-center">
            <p class="text-muted m-0 font-italic">Publié le <?= $post->getCreatedAt()->format('d/m/Y') ?></p>
                <a href="<?= $router->url('post',["id" => $post->getId(), "slug" => $post->getSlug()]) ?>" class="btn btn-primary text-right">Voir plus</a>
            </div>
        </div>
    </div>
</div>
