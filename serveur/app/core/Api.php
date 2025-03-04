<?php
require_once('./app/controllers/ExempleController.php');
require_once('./app/controllers/AuthController.php');

function setupRoutes($router) {
    $router->add('GET', "/api/home", [ExempleController::class, 'index']);
    $router->add('POST', "/api/auth/login", [AuthController::class, 'login']);
    $router->add('POST', "/api/auth/register", [AuthController::class, 'register']);
}
