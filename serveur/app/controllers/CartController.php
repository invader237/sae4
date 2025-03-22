<?php
require_once('./app/core/AuthMiddleware.php');
require_once('./app/services/CartService.php');
require_once('./app/entity/Cart.php');
require_once('./app/entity/Product.php');

class CartController {
    public static function getCart() {
        header('Content-Type: application/json');
        
        $idUser=AuthMiddleware::getUser();
        $cart = CartService::getCart($idUser);
        echo json_encode(["data" => $cart->toArray()], JSON_UNESCAPED_UNICODE);
    }

    public static function addProduct() {
        header('Content-Type: application/json');
        
        $idUser=AuthMiddleware::getUser();

        $body = file_get_contents('php://input');
        $data = json_decode($body, true); 

        $produitId = $data['idProduct'] ?? null;
        $quantity = $data['quantity'] ?? null;
        $idColor = $data['idColor'] ?? null;
        $idSize = $data['idSize'] ?? null;

        $idUser=AuthMiddleware::getUser();

        CartService::addProduct($idUser, $produitId, $quantity, $idColor, $idSize);

        echo json_encode(["success" => true, "message" => "Produit ajouté ou mis à jour avec succès"]);
    }

    public static function removeProduct() {
        header("Content-Type: application/json");
        
        $idUser=AuthMiddleware::getUser();

        $body = file_get_contents('php://input');
        $data = json_decode($body, true); 

        $produitId = $data['idProduct'] ?? null;
        $idColor = $data['idColor'] ?? null;
        $idSize = $data['idSize'] ?? null;

        CartService::removeProduct($idUser, $produitId, $idColor, $idSize);

        echo json_encode(["success" => true, "message" => "Produit supprimé avec succès"]);
    }

    public static function removeAllProduct() {
        header("Content-Type: application/json");
        
        $idUser=AuthMiddleware::getUser();

        CartService::removeAllProduct($idUser);

        echo json_encode(["success" => true, "message" => "Tous les produits ont été supprimés avec succès"]);

    }

}
