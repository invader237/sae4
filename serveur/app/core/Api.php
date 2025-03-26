<?php
require_once('./app/controllers/AuthController.php');
require_once('./app/controllers/ProductController.php');
require_once('./app/controllers/SizeController.php');
require_once('./app/controllers/ColorController.php');
require_once('./app/controllers/CartController.php');
require_once('./app/controllers/UserController.php');
require_once('./app/controllers/DeliveryController.php');
require_once('./app/controllers/CategoryController.php');
require_once('./app/controllers/OrderController.php');
require_once('./app/controllers/SkuController.php');

function setupRoutes($router) {
    $router->add('POST', "/api/auth/login", [AuthController::class, 'login']);
    $router->add('POST', "/api/auth/register", [AuthController::class, 'register']);
    $router->add('GET', "/api/getAllProducts", [ProductController::class, "getAllProducts"]);
    $router->add('GET', "/api/getProductByIdAndColorAndSize", [ProductController::class, "getProductByIdAndColorAndSize"]);
    $router->add("GET","/api/getSizesByProductId", [SizeController::class, "getSizesByProductId"]);
    $router->add("GET","/api/getColorsByProductId", [ColorController::class, "getColorsByProductId"]);
    $router->add('GET',"/api/getPanier", [CartController::class, "getCart"]);
    $router->add('POST',"/api/addProduit", [CartController::class, "addProduct"]);
    $router->add('DELETE', '/api/deleteProduct', [CartController::class, 'removeProduct']);
    $router->add('DELETE', '/api/deleteAllProducts', [CartController::class, 'removeAllProduct']);
    $router->add('GET', '/api/getUser', [UserController::class, 'getUser']);
    $router->add('GET', '/api/delivery', [DeliveryController::class, 'getAll']);
    $router->add('GET', '/api/getColor', [ColorController::class, 'getAll']);
    $router->add('GET', '/api/getSize', [SizeController::class, 'getAll']);
    $router->add('GET', '/api/getCategory', [CategoryController::class, 'getAll']);
    $router->add('POST', '/api/createOrder', [OrderController::class, 'validateOrder']);
    $router->add('GET', '/api/getAllOrders', [OrderController::class, 'getAll']);
    $router->add('GET', '/api/getOrderById', [OrderController::class, 'getOrderById']);
    $router->add('GET', '/api/getSku', [SkuController::class, 'getSkusByColorAndSize']);

}
