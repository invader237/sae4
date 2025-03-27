<?php
require_once('./app/DAO/SkuDAO.php');
require_once('./app/core/Connexion.php');

class SkuService {
    public static function getSkusByColorAndSize($id, $idColor, $idSize) {
        $db = Database::getConnection();
        $skuDAO = new SkuDAO($db);
        $sku = $skuDAO->getSkusByColorAndSize($id, $idColor, $idSize);
        return $sku;
    }

    public static function reduceStock($id, $idColor, $idSize, $quantity) {
        $db = Database::getConnection();
        $skuDAO = new SkuDAO($db);
        $skuDAO->reduceStock($id, $idColor, $idSize, $quantity);
    }

}
