<?php

use App\Table\Connection;
use App\Table\PostTable;
use App\Auth;

Auth::check();

$pdo = Connection::getPDO();
$postManager = new PostTable($pdo);
$result = $postManager->delete($params['id']);
header('Location: ' . $router->url('admin_posts') . '?deleted=1');
?>