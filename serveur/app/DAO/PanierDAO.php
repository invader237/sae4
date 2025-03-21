<?php
require_once './app/entity/Panier.php';
require_once './app/entity/Product.php';
require_once './app/entity/Color.php';
require_once './app/entity/Size.php';

class PanierDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getPanier($id_utilisateur) {
        $stmt = $this->pdo->prepare(
            "SELECT * 
            FROM PANIER, CONTENU_PANIER, PRODUIT, COULEUR, TAILLE, COULEUR_PRODUIT
            where CONTENU_PANIER.id_produit = PRODUIT.id_produit
            AND CONTENU_PANIER.id_couleur = COULEUR.id_couleur
            AND CONTENU_PANIER.id_taille = TAILLE.id_taille
            AND COULEUR_PRODUIT.id_couleur = CONTENU_PANIER.id_couleur
            AND COULEUR_PRODUIT.id_produit = CONTENU_PANIER.id_produit
            AND PANIER.id_panier = CONTENU_PANIER.id_panier
            AND PANIER.id_utilisateur = :id_utilisateur;"
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
                        $row['id_categorie'],
                    ),
                    'quantity' => $row['qte']
                ];

                $produits[] = $produit;
            }

            return new Panier($id_panier, $id_utilisateur, $produits);
    }

    public function addProduit($id_utilisateur, $id_produit, $quantite, $id_couleur, $id_taille): void {
        $stmt = $this->pdo->prepare(
            'INSERT INTO CONTENU_PANIER (id_panier, id_produit, qte, id_taille, id_couleur)
            SELECT p.id_panier, :id_produit, :quantite , :id_couleur, :id_taille
            FROM PANIER p
            WHERE p.id_utilisateur = :id_utilisateur
            ON DUPLICATE KEY UPDATE
                qte = VALUES(qte);
            ');

        $stmt->execute([
            'id_utilisateur' => $id_utilisateur,
            'id_produit' => $id_produit,
            'quantite' => $quantite,
            'id_couleur' => $id_couleur,
            'id_taille' => $id_taille
        ]);
    }
}
?>
