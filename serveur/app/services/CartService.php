<?php
require_once('./app/DAO/CartDAO.php');
require_once('./app/core/Connexion.php');

class CartService{
    public static function getCart($idUser){
        $db=Database::getConnection();
        $cartDAO=new CartDAO($db);
        $cart=$cartDAO->getCart($idUser);
        return $cart;
    }

    public static function addProduct($idUser,$produitId,$quantity,$idColor,$idSize): void{
        $db=Database::getConnection();
        $cartDAO=new CartDAO($db);
        $cart=$cartDAO->addProduct($idUser,$produitId,$quantity,$idColor,$idSize);
    }

    public static function removeProduct($idUser,$produitId,$idColor,$idSize): void{
        $db=Database::getConnection();
        $cartDAO=new CartDAO($db);
        $cart=$cartDAO->removeProduct($idUser,$produitId,$idColor,$idSize);
    }

    public static function removeAllProduct($idUser): void{
        $db=Database::getConnection();
        $cartDAO=new CartDAO($db);
        $cart=$cartDAO->removeAllProduct($idUser);
    }
}
?>
