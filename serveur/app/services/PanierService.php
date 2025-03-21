<?php
require_once('./app/DAO/PanierDAO.php');
require_once('./app/core/Connexion.php');

class PanierService{
    public static function getPanier($id_utilisateur){
        $db=Database::getConnection();
        $panierDAO=new PanierDAO($db);
        $panier=$panierDAO->getPanier($id_utilisateur);
        return $panier;
    }

    public static function addProduit($id_utilisateur,$produitId,$quantite,$id_couleur,$id_taille): void{
        $db=Database::getConnection();
        $panierDAO=new PanierDAO($db);
        $panier=$panierDAO->addProduit($id_utilisateur,$produitId,$quantite,$id_couleur,$id_taille);
    }
}
?>
