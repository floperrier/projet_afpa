<?php

use App\Connection;
use App\Model\{Category,Post};

$id = $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$query = $pdo->prepare("SELECT * FROM post WHERE id = :id");
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS,Post::class);

/** @var Post|bool */
$post = $query->fetch();

// SI l'id de l'url ne correspond à rien dans la bdd, on s'arrête
if ($post === false) {
    throw new Exception("L'article associé à l'id de l'URL n'existe pas");
}

// Sinon on vérifie que le slug correspond, si ce n'est pas le cas on redirige vers la bonne url
if ($slug !== $post->getSlug()) {
    http_response_code(301);
    header('Location: ' . $router->url('post',['slug' => $post->getSlug(), 'id' => $post->getId()]));
}

// On récupère les catégories associées
$query = $pdo->prepare("
SELECT * FROM category
INNER JOIN post_category pc ON pc.category_id = category.id
WHERE pc.post_id = :id");

$query->execute(['id' => $post->getId()]);
$query->setFetchMode(PDO::FETCH_CLASS,Category::class);
/** @var Category[] */
$categories = $query->fetchAll();
?>

<h1><?= htmlentities($post->getName()) ?></h1>
<?php
foreach($categories as $k => $category):
    $url = $router->url('category',['slug' => $category->getSlug(), 'id' => $category->getId()]);
    ?><a class="badge badge-info mr-2 p-2" href="<?= $url ?>"><?= htmlentities($category->getName()) ?></a><?php 
endforeach ?>
<p class="text-muted"><?= $post->getCreatedAt()->format('d/m/Y') ?></p>
<p class="card-text"><?= $post->getFormattedContent() ?></p>