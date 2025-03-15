<?php
require_once('./app/dao/PanierDAO.php');
require_once('./app/core/AuthMiddleware.php');

class PanierController {
    public function getPanier() {
        header('Content-Type: application/json');
        
        $id_utilisateur=AuthMiddleware::getUser();
        $panier = PanierService::getPanier($id_utilisateur);
        echo json_encode(["data" => $panier], JSON_UNESCAPED_UNICODE);
    }

    public function addProduitPanier() {
        header('Content-Type: application/json');
        
        $id_utilisateur=AuthMiddleware::getUser();
<<<<<<< HEAD
        
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);
=======
>>>>>>> a8f4f0f (:construction: WIP)

        if (!isset($data['id_produit'], $data['qte'], $data['id_taille'], $data['id_couleur'])) {
            http_response_code(400);
            echo json_encode(["message" => "Tous les champs sont requis"]);
            return;
        }
        
        $success = PanierService::addProduitPanier(
            $id_utilisateur, 
            $data['id_produit'], 
            $data['qte'], 
            $data['id_taille'], 
            $data['id_couleur']
        );
        echo json_encode(["message" => $success ? "Produit ajouté" : "Erreur lors de l'ajout"]);    
    }
    

    public function updateQuantiteProduit() {
        header('Content-Type: application/json');
        
        $id_utilisateur=AuthMiddleware::getUser();

        $body = file_get_contents('php://input');
        $data = json_decode($body, true);
        
        if (!isset($data['id_produit'], $data['qte'], $data['id_taille'], $data['id_couleur'])) {
            http_response_code(400);
            echo json_encode(["message" => "Tous les champs sont requis"]);
            return;
        }
        
        $success =PanierService::updateQuantiteProduit($id_utilisateur, $data['id_produit'], $data['qte'], $data['id_taille'], $data['id_couleur']);
        echo json_encode(["message" => $success ? "Quantité mise à jour" : "Erreur lors de la mise à jour"]);
    }

    public function deleteProduitPanier() {
        header('Content-Type: application/json');
        
        $id_utilisateur=AuthMiddleware::getUser();

        $body = file_get_contents('php://input');
        $data = json_decode($body, true);
        
        if (!isset($data['id_produit'], $data['id_taille'], $data['id_couleur'])) {
            http_response_code(400);
            echo json_encode(["message" => "Tous les champs sont requis"]);
            return;
        }
        
        $success = PanierService::deleteProduitPanier($id_utilisateur, $data['id_produit'], $data['id_taille'], $data['id_couleur']);
        echo json_encode(["message" => $success ? "Produit supprimé" : "Erreur lors de la suppression"]);
    }

    public function clearPanier() {
        header('Content-Type: application/json');

        $id_utilisateur=AuthMiddleware::getUser();
        
        $success = PanierSerivce::clearPanier($id_utilisateur);
        echo json_encode(["message" => $success ? "Panier vidé" : "Erreur lors du vidage du panier"]);
    }
}
