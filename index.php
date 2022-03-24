<?php

require_once 'vendor/autoload.php';

use App\Core\DatabaseConfiguration;
use App\Core\DatabaseConnection;

$databaseConfiguration = new DatabaseConfiguration('localhost', 'root', '', 'web_project');
$databaseConnection = new DatabaseConnection($databaseConfiguration);


$controller = new \App\Controllers\MainController($databaseConnection);
$controller->home();
$data = $controller->getData();

foreach ($data as $name => $value) {
    $$name = $value;
}

require_once 'views/Main/Home.php';
