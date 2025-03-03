<?php
require_once('./app/services/ProductService.php');

class ProductController
{
    public static function getAllProducts()
    {
        header('Content-Type: application/json');

        $products = ProductController::getAllProducts();

        try {
            echo json_encode(["data" => $products]);
        } catch (\Throwable $th) {
            http_response_code(500);
            echo json_encode(["message" => "Une erreur interne est survenue. Veuillez réessayer plus tard."]);
        }
    }
}
