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

}
