<div class="card col-md-3 m-2">
    <div class="card-body">
        <h5 class="card-title"><?= htmlentities($post->getName()) ?></h5>
        <p class="text-muted"><?= $post->getCreatedAt()->format('d/m/Y') ?></p>
        <p class="card-text"><?= $post->getExcerpt() ?></p>
        <a href="<?= $router->url('post',["id" => $post->getId(), "slug" => $post->getSlug()]) ?>" class="btn btn-primary">Voir plus</a>
    </div>
</div>