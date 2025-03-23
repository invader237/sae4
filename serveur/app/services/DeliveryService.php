<?php
require_once('./app/DAO/DeliveryDAO.php');
require_once('./app/entity/Delivery.php');
require_once('./app/core/Connexion.php');

class DeliveryService {
    public static function getAll() {
        $db = Database::getConnection();
        $deliveryDAO = new DeliveryDAO($db);
        $deliverys = $deliveryDAO->getAll();
        return $deliverys;
    }
}
