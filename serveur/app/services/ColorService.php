<?php
require_once('./app/DAO/ColorDAO.php');
require_once('./app/entity/Color.php');
require_once('./app/core/Connexion.php');

class ColorService {
    public static function getAll() {
        $db = Database::getConnection();
        $colorDAO = new ColorDAO($db);
        $colors = $colorDAO->getAll();
        return $colors;
    }

    public static function getColorsByProductId($id) {
        $db = Database::getConnection();
        $colorDAO = new ColorDAO($db);
        $colors = $colorDAO->getColorsByProductId($id);
        return $colors;
    }
}
