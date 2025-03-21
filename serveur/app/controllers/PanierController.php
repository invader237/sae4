<?php
require_once('./app/core/AuthMiddleware.php');
require_once('./app/services/PanierService.php');
require_once('./app/entity/Panier.php');
require_once('./app/entity/Product.php');

class PanierController {
    public static function getPanier() {
        header('Content-Type: application/json');
        
        $id_utilisateur=AuthMiddleware::getUser();
        $panier = PanierService::getPanier($id_utilisateur);
        echo json_encode(["data" => $panier->toArray()], JSON_UNESCAPED_UNICODE);
    }

    public static function addProduit() {
        header('Content-Type: application/json');

        $body = file_get_contents('php://input');
        $data = json_decode($body, true); 

        $produitId = $data['produit_id'] ?? null;
        $quantite = $data['quantite'] ?? null;
        $id_couleur = $data['id_couleur'] ?? null;
        $id_taille = $data['id_taille'] ?? null;

        $id_utilisateur=AuthMiddleware::getUser();

        PanierService::addProduit($id_utilisateur, $produitId, $quantite, $id_couleur, $id_taille);

        echo json_encode(["success" => true, "message" => "Produit ajouté ou mis à jour avec succès"]);
    }

}
