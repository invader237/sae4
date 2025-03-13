<?php
require_once('./app/dao/PanierDAO.php');

class PanierController {
    private $panierDAO;

    public function getPanier($id_utilisateur) {
        header('Content-Type: application/json');
        
        $panier = $this->panierDAO->getPanier($id_utilisateur);
        echo json_encode($panier);
    }

    public function addProduitPanier($id_utilisateur) {
        header('Content-Type: application/json');
        
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);
        
        if (!isset($data['id_produit'], $data['qte'], $data['id_taille'], $data['id_couleur'])) {
            http_response_code(400);
            echo json_encode(["message" => "Tous les champs sont requis"]);
            return;
        }
        
        $success = $this->panierDAO->addProduitPanier($id_utilisateur, $data['id_produit'], $data['qte'], $data['id_taille'], $data['id_couleur']);
        echo json_encode(["message" => $success ? "Produit ajouté" : "Erreur lors de l'ajout"]);
    }

    public function updateQuantiteProduit($id_utilisateur) {
        header('Content-Type: application/json');
        
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);
        
        if (!isset($data['id_produit'], $data['qte'], $data['id_taille'], $data['id_couleur'])) {
            http_response_code(400);
            echo json_encode(["message" => "Tous les champs sont requis"]);
            return;
        }
        
        $success = $this->panierDAO->updateQuantiteProduit($id_utilisateur, $data['id_produit'], $data['qte'], $data['id_taille'], $data['id_couleur']);
        echo json_encode(["message" => $success ? "Quantité mise à jour" : "Erreur lors de la mise à jour"]);
    }

    public function deleteProduitPanier($id_utilisateur) {
        header('Content-Type: application/json');
        
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);
        
        if (!isset($data['id_produit'], $data['id_taille'], $data['id_couleur'])) {
            http_response_code(400);
            echo json_encode(["message" => "Tous les champs sont requis"]);
            return;
        }
        
        $success = $this->panierDAO->deleteProduitPanier($id_utilisateur, $data['id_produit'], $data['id_taille'], $data['id_couleur']);
        echo json_encode(["message" => $success ? "Produit supprimé" : "Erreur lors de la suppression"]);
    }

    public function clearPanier($id_utilisateur) {
        header('Content-Type: application/json');
        
        $success = $this->panierDAO->clearPanier($id_utilisateur);
        echo json_encode(["message" => $success ? "Panier vidé" : "Erreur lors du vidage du panier"]);
    }
}