<?php

use App\Connection;
use App\Table\PostTable;

$pdo = Connection::getPDO();
$postManager =  new PostTable($pdo);
[$posts,$pagination] = $postManager->findPaginated();
$link = $router->url("admin");
?>