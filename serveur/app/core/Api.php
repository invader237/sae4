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
    $router->add("GET","/api/getSizesByProductId", [ProductController::class, "getSizesByProductId"]);
    $router->add("GET","/api/getColorsByProductId", [ProductController::class, "getColorsByProductId"]);
    $router->add("GET","/api/getAllSizes", [SizeController::class, "getAllSizes"]);
    $router->add("GET","/api/getSizeById", [SizeController::class, "getSizeById"]);
    $router->add("GET","/api/getAllColors", [ColorController::class, "getAllColors"]);
    $router->add("GET","/api/getColorById", [ColorController::class, "getColorById"]);
}
