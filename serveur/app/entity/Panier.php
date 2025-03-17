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

    public function getIdPanier(): int {
        return $this->id_panier;
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

    public function setIdPanier(int $id_panier): void {
        $this->id_panier = $id_panier;
    }

    public function setIdUtilisateur(int $id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }

    public function setProduits(array $produits): void {
        $this->produits = $produits;
    }


    public function toArray(): array {
        $produitsArray = [];

        foreach ($this->produits as $idProduit => $item) {
            $produitsArray[] = [
                'product' => $item['product']->toArray(), 
                'quantity' => $item['quantity']
            ];
        }

        return [
            "id_panier" => $this->id_panier,
            "id_utilisateur" => $this->id_utilisateur,
            "produits" => $produitsArray
        ];
    }
}
?>
