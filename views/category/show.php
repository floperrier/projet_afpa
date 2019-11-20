<?php

use App\Connection;
use App\Model\{Category,Post};
use App\PaginatedQuery;
use App\URL;

$id = $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();

$query = $pdo->prepare("SELECT * FROM category WHERE id = :id");
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS,Category::class);

/** @var Category|bool */
$category = $query->fetch();

// SI l'id de l'url ne correspond à rien dans la bdd, on s'arrête
if ($category === false) {
    throw new Exception("La catégorie associée à l'id de l'URL n'existe pas");
}

// Sinon on vérifie que le slug correspond, si ce n'est pas le cas on redirige vers la bonne url
if ($slug !== $category->getSlug()) {
    http_response_code(301);
    header('Location: ' . $router->url('category',['slug' => $category->getSlug(), 'id' => $category->getId()]));
}

$paginatedQuery = new PaginatedQuery(
    "SELECT count(category_id) FROM post_category pc
    WHERE pc.category_id = {$category->getId()}",
    "SELECT post.* FROM post
    INNER JOIN post_category pc ON pc.post_id = post.id
    WHERE pc.category_id = {$category->getId()}",
    Post::class
);
$posts = $paginatedQuery->getItems();
foreach ($posts as $post) {
    $postsById[$post->getId()] = $post;
}
$categories = $pdo
    ->query("SELECT c.*, pc.post_id FROM post_category pc
            JOIN category c ON pc.category_id = c.id
            WHERE pc.post_id IN (" . implode(',',array_keys($postsById)) . ")")
    ->fetchAll(PDO::FETCH_CLASS,Category::class);

foreach($categories as $category) {
    $postsById[$category->getPostId()]->addCategory($category);
}
$link = $router->url('category',['id' => $category->getId(), 'slug' => $category->getSlug()]);
?>

<h1>Catégorie "<?= htmlentities($category->getName()) ?>"</h1>

<div class="row">
    <?php foreach ($posts as $post): ?>
        <?php require dirname(__DIR__) . '/post/card.php' ?>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
<?= $paginatedQuery->previousLink($link) ?>
<?= $paginatedQuery->nextLink($link) ?>
</div>