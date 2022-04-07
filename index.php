<?php

require_once 'vendor/autoload.php';
require_once 'Configuration.php';

use App\Core\DatabaseConfiguration;
use App\Core\DatabaseConnection;

$databaseConfiguration = new DatabaseConfiguration(
    Configuration::DATABASE_HOST,
    Configuration::DATABASE_USER,
    Configuration::DATABASE_PASS,
    Configuration::DATABASE_NAME
);
$databaseConnection = new DatabaseConnection($databaseConfiguration);

$url = strval(filter_input(INPUT_GET, 'URL'));
$httpMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD');

$router = new App\Core\Router();
$routes = require_once 'Routes.php';
foreach ($routes as $route) {
    $router->add($route);
}

$route = $router->find($httpMethod, $url);
$arguments = $route->extractArguments($url);

// print_r($route);
// print_r($arguments);
// exit;



$fullControllerName = '\\App\\Controllers\\' . $route->getControllerName() . 'Controller';
$controller = new $fullControllerName($databaseConnection);
call_user_func_array([$controller, $route->getMethodName()], $arguments);
$data = $controller->getData();


$loader = new \Twig\Loader\FilesystemLoader("./views");
$twig = new \Twig\Environment($loader, [
    //'cache' => './twig-cache',
    "auto-reload" => true
]);

echo $twig->render($route->getControllerName() . '/' . $route->getMethodName() . '.html', $data);
