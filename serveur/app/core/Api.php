<?php
require_once('./app/controllers/ExempleController.php');
require_once('./app/controllers/AuthController.php');
require_once('./app/controllers/ProductController.php');

function setupRoutes($router) {
    $router->add('GET', "/api/home", [ExempleController::class, 'index']);
    $router->add('POST', "/api/auth/login", [AuthController::class, 'login']);
    $router->add('POST', "/api/auth/register", [AuthController::class, 'register']);
    $router->add('GET', "/api/getAllProducts", [ProductController::class, "getAllProducts"]);
    $router->add('GET', "/api/getProductById", [ProductController::class, "getProductById"]);
}
