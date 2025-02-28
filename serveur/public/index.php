<?php
header("Content-Type: application/json");

require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Controllers/ExempleController.php';

$router = new Router();
$router->add('GET', '/api/home', [new ExempleController() , 'index']);
//add new route here below

$router->dispatch();
