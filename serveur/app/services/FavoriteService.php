<?php
require_once('./app/DAO/FavoriteDAO.php');
require_once('./app/core/Connexion.php');

class FavoriteService{
    public static function getFavorite($idUser){
        $db=Database::getConnection();
        $favoriteDAO=new FavoriteDAO($db);
        $favorite=$favoriteDAO->getCart($idUser);
        return $favorite;
    }

    public static function addFavorite($idUser,$idProduct,$idSize,$idColor):void{
        $db=Database::getConnection();
        $favoriteDAO=new FavoriteDAO($db);
        $favorite=$favoriteDAO->addFavorite($idUser,$idProduct,$idSize,$idColor);
    }
    public static function removeFavorite($idUser, $idProduct, $idColor, $idSize):void{
        $db=Database::getConnection();
        $favoriteDAO=new FavoriteDAO($db);
        $favorite=$favoriteDAO->removeFavorite($idUser, $idProduct, $idColor, $idSize);
    }
    public static function removeAllFavorites($idUser):void{
        $db=Database::getConnection();
        $favoriteDAO=new FavoriteDAO($db);
        $favorite=$favoriteDAO->removeAllFavorites($idUser);
    }
}
?>