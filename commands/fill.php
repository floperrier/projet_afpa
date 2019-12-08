<?php

use App\Table\Connection;

require dirname(__DIR__) . '/vendor/autoload.php';

$pdo = Connection::getPDO();

$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("TRUNCATE TABLE user");
$pdo->exec("TRUNCATE TABLE post");
$pdo->exec("TRUNCATE TABLE post_category");
$pdo->exec("TRUNCATE TABLE category");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

$faker = Faker\Factory::create('fr_FR');

$posts = [];
$categories = [];
$users = [];

for ($i = 1; $i < 3; $i++) {
    $password = password_hash("admin{$i}",PASSWORD_BCRYPT);
    $pdo->exec("INSERT INTO user SET username='admin{$i}', password='{$password}'");
    $users[] = $pdo->lastInsertId();
}

for ($i = 0; $i < 50; $i++) {
    $author = rand(1,count($users));
    $pdo->exec("INSERT INTO post SET author_id ='{$author}', name='{$faker->sentence()}', slug='{$faker->slug}', content='{$faker->paragraphs(3,true)}', created_at='{$faker->date}'");
    $posts[] = $pdo->lastInsertId();
}

for ($i = 0; $i < 5; $i++) {
    $pdo->exec("INSERT INTO category SET name='{$faker->sentence(3)}', slug='{$faker->slug}'");
    $categories[] = $pdo->lastInsertId();
}

foreach ($posts as $post) {
    $randomCategories = $faker->randomElements($categories,rand(0,count($categories)));
    foreach ($randomCategories as $category) {
        $pdo->exec("INSERT INTO post_category SET post_id='{$post}', category_id='{$category}'");
    }
}

