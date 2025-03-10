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

    public static function getProductById() {
        header('Content-Type: application/json');

        $body = file_get_contents('php://input');
        $data = json_decode($body, true); 

        $id = $data['id'] ?? null;

        $product = ProductService::getProductById($id);
        if ($product) {
            echo json_encode(["data" => $product->toArray()], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Produit non trouvé."]);
        }
    }

    public static function getSizesByProductId() {
        header('Content-Type: application/json');

        $body = file_get_contents('php://input');
        $data = json_decode($body, true); 

        $id = $data['id'] ?? null;

        $sizes = ProductService::getSizesByProductId($id);
        if ($sizes) {
            $sizesArray = array_map(fn($size) => $size->toArray(), $sizes);
            echo json_encode(['data'=> $sizesArray], JSON_UNESCAPED_UNICODE);
        } else {
            
            http_response_code(404);
            echo json_encode(["message" => "Produit non trouvé."]);
        }
    }

    public static function getColorsByProductId() {
        header('Content-Type: application/json');

        $body = file_get_contents('php://input');
        $data = json_decode($body, true); 

        $id = $data['id'] ?? null;

        $colors = ProductService::getColorsByProductId($id);
        if ($colors) {
            $colorsArray = array_map(fn($color) => $color->toArray(), $colors);
            echo json_encode(['data'=> $colorsArray], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Produit non trouvé."]);
        }
    }
}