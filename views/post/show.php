<?php

use App\Table\Connection;
use App\Model\{Category,Post};
use App\Table\CategoryTable;
use App\Table\PostTable;

$id = $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$post = (new PostTable($pdo))->find($id);

// on vérifie que le slug correspond, si ce n'est pas le cas on redirige vers la bonne url
if ($slug !== $post->getSlug()) {
    http_response_code(301);
    header('Location: ' . $router->url('post',['slug' => $post->getSlug(), 'id' => $post->getId()]));
}

//Si c'est bon, on hydrate l'objet Post avec les catégories
(new CategoryTable($pdo))->hydratePosts([$post]);
?>

<h1><?= htmlentities($post->getName()) ?></h1>
<?php
foreach($post->getCategories() as $k => $category):
    $url = $router->url('category',['slug' => $category->getSlug(), 'id' => $category->getId()]);
    ?><a class="badge badge-info mr-2 p-2" href="<?= $url ?>"><?= htmlentities($category->getName()) ?></a><?php 
endforeach ?>
<p class="text-muted"><?= $post->getCreatedAt()->format('d/m/Y') ?></p>
<p class="card-text"><?= $post->getFormattedContent() ?></p>