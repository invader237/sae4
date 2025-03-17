<?php
class Panier{
    private $id_panier;
    private $id_utilisateur;
    private $id_produit;
    private $qte;
    private $id_taille;
    private $id_couleur;

    public function __construct($id_panier,$id_utilisateur,$id_produit,$qte,$id_taille,$id_couleur){
        $this->id_panier=$id_panier;
        $this->id_utilisateur=$id_utilisateur;
        $this->id_produit=$id_produit;
        $this->qte=$qte;
        $this->id_taille=$id_taille;
        $this->id_couleur=$id_couleur;
    }

    public function getIdPanier(){
        return $this->id_panier;
    }
    public function getIdUtilisateur(){
        return $this->id_utilisateur;
    }
    public function getIdProduit(){
        return $this->id_produit;
    }
    public function getQte(){
        return $this->qte;
    }
    public function getIdTaille(){
        return $this->id_taille;
    }
    public function getIdCouleur(){
        return $this->id_couleur;
    }

    public function setIdPanier($id_panier){
        $this->id_panier=$id_panier;
    }
    public function setIdUtilisateur($id_utilisateur){
        $this->id_utilisateur=$id_utilisateur;
    }
    public function setIdProduit($id_produit){
        $this->id_produit=$id_produit;
    }
    public function setQte($qte){
        $this->qte=$qte;
    }
    public function setIdTaille($id_taille){
        $this->id_taille=$id_taille;
    }
    public function setIdCouleur($id_couleur){
        $this->id_couleur=$id_couleur;
    }
}
?>
