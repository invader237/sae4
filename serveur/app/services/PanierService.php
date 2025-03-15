<?php
require_once('./app/DAO/PanierDAO.php');
require_once('./app/entity/Panier.php');
require_once('./app/core/Connexion.php');

class PanierService{
    public static function getPanier($id_utilisateur){
        $db=Database::getConnection();
        $panierDAO=new PanierDAO($db);
        $panier=$panierDAO->getPanier($id_utilisateur);
        return $panier;
    }
}
?>