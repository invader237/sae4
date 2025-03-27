<?php
require_once('./app/DAO/FavoriteDAO.php');
require_once('./app/core/Connexion.php');

class FavoriteService{
    public static function getFavorites($idUser){
        $db=Database::getConnection();
        $favoriteDAO=new FavoriteDAO($db);
        $favorite=$favoriteDAO->getFavorites($idUser);
        return $favorite;
    }

    public static function addFavorites($idUser,$idProduct,$idSize,$idColor):void{
        $db=Database::getConnection();
        $favoriteDAO=new FavoriteDAO($db);
        $favorite=$favoriteDAO->addFavorites($idUser,$idProduct,$idSize,$idColor);
    }
    public static function removeFavorites($idUser, $idProduct, $idSize, $idColor):void{
        $db=Database::getConnection();
        $favoriteDAO=new FavoriteDAO($db);
        $favorite=$favoriteDAO->removeFavorites($idUser, $idProduct, $idSize, $idColor);
    }
    public static function removeAllFavorites($idUser):void{
        $db=Database::getConnection();
        $favoriteDAO=new FavoriteDAO($db);
        $favorite=$favoriteDAO->removeAllFavorites($idUser);
    }
}
?>