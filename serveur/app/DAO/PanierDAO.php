<?php
require_once './app/entity/Panier.php';
require_once './app/entity/Product.php';

class PanierDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getPanier($id_utilisateur) {
        $stmt = $this->pdo->prepare(
            "SELECT * 
            FROM PANIER, CONTENU_PANIER, PRODUIT, COULEUR_PRODUIT
            WHERE PANIER.id_produit = PRODUIT.id_produit
            AND PRODUIT.id_produit = COULEUR_PRODUIT.id_produit
            AND PANIER.id_produit = CONTENU_PANIER.id_produit
            AND PANIER.id_utilisateur = :id_utilisateur ;"
        );
            $stmt->execute(['id_utilisateur' => $id_utilisateur]);
            
            if ($stmt->rowCount() === 0) {
                return null;
            }

            $produits = []; 
            $id_panier = null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                if ($id_panier === null) {
                    $id_panier = $row['id_panier']; 
                }

                $produit = [
                    'product' => new Product(
                        $row['id_produit'],
                        $row['designation'],
                        $row['description'],
                        $row['prix'],
                        $row['url_image'],
                        $row['id_categorie']
                    ),
                    'quantity' => $row['qte']
                ];

                $produits[] = $produit;
            }

            return new Panier($id_panier, $id_utilisateur, $produits);
    }
}
?>
