<?php
require_once('./app/DAO/SizeDAO.php');
require_once('./app/entity/Size.php');
require_once('./app/core/Connexion.php');

class SizeService {
    public static function getSizesByProductId($id) {
        $db = Database::getConnection();
        $sizeDAO = new SizeDAO($db);
        $sizes = $sizeDAO->getSizesByProductId($id);
        return $sizes;
    }
}