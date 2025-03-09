<?php
require_once('./app/DAO/ColorDAO.php');
require_once('./app/entity/Color.php');
require_once('./app/core/Connexion.php');

class ColorService {
    public static function getAllColors() {
        $db = Database::getConnection();
        $colorDAO = new ColorDAO($db);
        $colors = $colorDAO->getAllColors();
        return $colors;
    }

    public static function getColorById($id) {
        $db = Database::getConnection();
        $colorDAO = new ColorDAO($db);
        $color = $colorDAO->getColorById($id);
        return $color;
    }
}