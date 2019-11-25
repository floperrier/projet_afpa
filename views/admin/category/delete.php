<?php

use App\Connection;
use App\Table\CategoryTable;
use App\Table\PostTable;

$pdo = Connection::getPDO();
$categoryTable = new CategoryTable($pdo);
$result = $categoryTable->delete($params['id']);
header('Location: ' . $router->url('admin_categories') . '?delete=1');
?>