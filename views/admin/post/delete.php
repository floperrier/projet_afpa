<?php

use App\Connection;
use App\Table\PostTable;

$pdo = Connection::getPDO();
$postManager = new PostTable($pdo);
$result = $postManager->delete($params['id']);
header('Location: ' . $router->url('admin_posts') . '?delete=1');
?>