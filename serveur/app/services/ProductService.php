<?php
require_once('./app/DAO/ProductDAO.php');
require_once('./app/entity/Product.php');
require_once('./app/core/Connexion.php');

class ProductService {
    public static function getAllProducts() {
        $db = Database::getConnection();
        $productDAO = new ProductDAO($db);
        $products = $productDAO->getAllProducts();
        return $products;
    }

    public static function searchProducts($search, $color, $size, $category) {
        $db = Database::getConnection();
        $productDAO = new ProductDAO($db);
        $products = $productDAO->searchProducts($search, $color, $size, $category);
        return $products;
    }

    public static function getProductByIdAndColorAndSize($id, $color, $size) {
        $db = Database::getConnection();
        $productDAO = new ProductDAO($db);
        $product = $productDAO->getProductByIdAndColorAndSize($id, $color, $size);
        return $product;
    }

}
