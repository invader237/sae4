<?php
require_once('./app/core/AuthMiddleware.php');
require_once('./app/services/FavoriteService.php');
require_once('./app/entity/Favorite.php');


class FavoriteController{
    public static function getFavorites(){
        header('Content-Type: application/json');

        $idUser=AuthMiddleware::getUser();
        $favorite = FavoriteService::getFavorites($idUser);
        echo json_encode(["data" => $favorite->toArray()], JSON_UNESCAPED_UNICODE);
    }

    public static function addFavorites() {
        header('Content-Type: application/json');
        
        $idUser=AuthMiddleware::getUser();

        $body = file_get_contents('php://input');
        $data = json_decode($body, true); 

        $idProduct = $data['idProduct'] ?? null;
        $idSize = $data['idSize'] ?? null;
        $idColor = $data['idColor'] ?? null;

        FavoriteService::addFavorites($idUser,$idProduct,$idSize,$idColor);

        echo json_encode(["success" => true, "message" => "Produit ajouté ou mis à jour comme favori, avec succès"]);
    }

    public static function removeFavorites(){
        header("Content-Type: application/json");
        
        $idUser=AuthMiddleware::getUser();

        $body = file_get_contents('php://input');
        $data = json_decode($body, true); 

        $idProduct = $data['idProduct'] ?? null;
        $idSize = $data['idSize'] ?? null;
        $idColor = $data['idColor'] ?? null;
        

        FavoriteService::removeFavorites($idUser, $idProduct,$idSize, $idColor);

        echo json_encode(["success" => true, "message" => "Favori supprimé avec succès"]);
    }

    public static function removeAllFavorites() {
        header("Content-Type: application/json");
        
        $idUser=AuthMiddleware::getUser();
        
        FavoriteService::removeAllFavorites($idUser);

        echo json_encode(["success" => true, "message" => "Tous les produits ont été supprimés avec succès"]);

    }

}
?>