<?php

use App\Connection;
use App\Model\{Category,Post};
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

// On récupère les posts associées
$query = $pdo->prepare("
SELECT * FROM post
INNER JOIN post_category pc ON pc.category_id = post.id
WHERE pc.post_id = :id");

$query->execute(['id' => $category->getId()]);
$query->setFetchMode(PDO::FETCH_CLASS,Post::class);
/** @var Post[] */
$posts = $query->fetchAll();

// On recupere le numero de la page depuis l'URL
$currentPage = URL::getPositiveInt('page',1);

// On compte le nombre de pages et on vérifie que la page appellée existe
$perPage = 12;
$articlesNumber = (int)$pdo->query("
SELECT count(category_id) FROM post_category pc
WHERE pc.category_id = {$category->getId()}")->fetch(PDO::FETCH_NUM)[0];
$pages = ceil($articlesNumber / $perPage);
if ($currentPage > $pages) {
    throw new Exception("Cette page n'existe pas");
}

// On récupère les articles à afficher
$offset = $perPage * ($currentPage - 1);
$posts = $pdo->query("
SELECT * FROM post 
INNER JOIN post_category pc ON pc.post_id = post.id
WHERE pc.category_id = {$category->getId()}
ORDER BY created_at DESC LIMIT 12 OFFSET $offset")->fetchAll(PDO::FETCH_CLASS,Post::class);

?>

<h1>Catégorie "<?= htmlentities($category->getName()) ?>"</h1>

<div class="row">
    <?php foreach ($posts as $post): ?>
        <?php require dirname(__DIR__) . '/post/card.php' ?>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
    <?php if ($currentPage > 1): ?>
    <a class="btn btn-primary" href="<?= $router->url('category',['id' => $category->getId(), 'slug' => $category->getSlug()]) ?>?page=<?= $currentPage - 1 ?>">Page précédente</a>
    <?php endif ?>
    <?php if ($currentPage < $pages): ?>
    <a class="btn btn-primary ml-auto" href="<?= $router->url('category',['id' => $category->getId(), 'slug' => $category->getSlug()]) ?>?page=<?= $currentPage + 1 ?>">Page suivante</a>
    <?php endif ?>
</div>