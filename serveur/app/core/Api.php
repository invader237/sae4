<?php
require_once('./app/controllers/ExempleController.php');

function setupRoutes($router) {
    $router->add('GET', "/api/home", [ExempleController::class, 'index']);
}
