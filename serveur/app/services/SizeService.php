<?php
require_once('./app/DAO/SizeDAO.php');
require_once('./app/entity/Size.php');
require_once('./app/core/Connexion.php');

class SizeService {
    public static function getAllSizes() {
        $db = Database::getConnection();
        $sizeDAO = new SizeDAO($db);
        $sizes = $sizeDAO->getAllSizes();
        return $sizes;
    }

    public static function getSizeById($id) {
        $db = Database::getConnection();
        $sizeDAO = new SizeDAO($db);
        $size = $sizeDAO->getSizeById($id);
        return $size;
    }
}