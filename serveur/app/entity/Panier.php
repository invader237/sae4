<?php
require_once('./app/entity/Product.php');

class Panier {
    private $id_panier;
    private $id_utilisateur;
    private array $produits = []; 

    public function __construct($id_panier, $id_utilisateur, $produits = []) {
        $this->id_panier = $id_panier;
        $this->id_utilisateur = $id_utilisateur;
        $this->produits = $produits; 
    }

    public function getIdPanier() {
        return $this->id_panier;
    }

    public function getIdUtilisateur() {
        return $this->id_utilisateur;
    }

    public function getProduits(): array {
        return $this->produits;
    }

    public function ajouterProduit($idProduit, $qte) {
        if (isset($this->produits[$idProduit])) {
            $this->produits[$idProduit] += $qte;
        } else {
            $this->produits[$idProduit] = $qte;
        }
    }

    public function retirerProduit($idProduit) {
        unset($this->produits[$idProduit]);
    }

    public function modifierQuantite($idProduit, $qte) {
        if (isset($this->produits[$idProduit])) {
            if ($qte > 0) {
                $this->produits[$idProduit] = $qte;
            } else {
                $this->retirerProduit($idProduit);
            }
        }
    }

    public function viderPanier() {
        $this->produits = [];
    }

    public function toArray() {
        return [
            "id_panier" => $this->id_panier,
            "id_utilisateur" => $this->id_utilisateur,
            "produits" => $this->produits
        ];
    }
}
?>
