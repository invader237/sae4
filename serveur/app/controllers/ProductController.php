<?php
require_once('./app/services/ProductService.php');

class ProductController {
    public static function getAllProducts() {
        header('Content-Type: application/json');

        $search = $_GET['search'] ?? null;  
        $color = $_GET['color'] ?? null;
        $size = $_GET['size'] ?? null;
        $category = $_GET['category'] ?? null;

        if ($search || $color || $size || $category) {
            $products = ProductService::searchProducts($search, $color, $size, $category);
            $productsArray = array_map(fn($product) => $product->toArray(), $products);
            echo json_encode(["data" => $productsArray], JSON_UNESCAPED_UNICODE);
        } else {

            $products = ProductService::getAllProducts();

            if ($products) {
                $productsArray = array_map(fn($product) => $product->toArray(), $products);
                echo json_encode(["data" => $productsArray], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Une erreur interne est survenue. Veuillez réessayer plus tard."]);
            }
        }
    }
}