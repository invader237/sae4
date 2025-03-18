<?php
require_once('./app/core/AuthMiddleware.php');
require_once('./app/services/PanierService.php');

class PanierController {
    public static function getPanier() {
        header('Content-Type: application/json');
        
        $id_utilisateur=AuthMiddleware::getUser();
        $panier = PanierService::getPanier($id_utilisateur);
        echo json_encode(["data" => $panier->toArray()], JSON_UNESCAPED_UNICODE);
    }

}
