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

    public function addProduit() {
        header('Content-Type: application/json');

        $produitId = intval($_POST['produit_id']);
        $quantite = intval($_POST['quantite']);
        $panierId = intval($_POST['panier_id']);
        $id_couleur = intval($_POST['id_couleur']);
        $id_taille = intval($_POST['id_taille']);

        $id_utilisateur=AuthMiddleware::getUser();

        if (!$panierId) {
            echo json_encode(["success" => false, "message" => "ID du panier non trouvé"]);
            return;
        }

        $result = PanierService::addProduit($id_utilisateur, $produitId, $quantite, $panierId, $id_couleur, $id_taille);

        if ($result) {
            echo json_encode(["success" => true, "message" => "Produit ajouté ou mis à jour avec succès"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout du produit"]);
        }
    }

}
