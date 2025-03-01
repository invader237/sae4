<?php
require_once('./app/core/Router.php');
require_once('./app/core/Api.php');

header('Content-Type: application/json');

$router = new Router();  
setupRoutes($router);
$router->dispatch();

