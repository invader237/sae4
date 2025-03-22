<?php
require_once './app/entity/Cart.php';
require_once './app/entity/Product.php';
require_once './app/entity/Color.php';
require_once './app/entity/Size.php';
require_once './app/entity/ProductDetail.php';

class CartDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getCart($idUser) {
        $stmt = $this->pdo->prepare(
            "SELECT PANIER.id_panier, PRODUIT.id_produit, PRODUIT.designation, PRODUIT.description,
            PRODUIT.prix,CONTENU_PANIER.qte, COULEUR_PRODUIT.url_image, PRODUIT.id_categorie,
            COULEUR.id_couleur, COULEUR.libelle as couleur_lib, COULEUR_PRODUIT.reduction as reduc_couleur, TAILLE.id_taille,
            TAILLE.libelle as taille_lib, TAILLE_PRODUIT.reduction as reduc_taille
            FROM PANIER, CONTENU_PANIER, PRODUIT, COULEUR, TAILLE, COULEUR_PRODUIT, TAILLE_PRODUIT
            WHERE CONTENU_PANIER.id_produit = PRODUIT.id_produit
            AND CONTENU_PANIER.id_couleur = COULEUR.id_couleur
            AND CONTENU_PANIER.id_taille = TAILLE.id_taille
            AND COULEUR_PRODUIT.id_couleur = CONTENU_PANIER.id_couleur
            AND COULEUR_PRODUIT.id_produit = CONTENU_PANIER.id_produit
            AND TAILLE_PRODUIT.id_taille = CONTENU_PANIER.id_taille
            AND TAILLE_PRODUIT.id_produit = CONTENU_PANIER.id_produit
            AND PANIER.id_panier = CONTENU_PANIER.id_panier
            AND PANIER.id_utilisateur = :idUser;"
        );
        $stmt->execute(['idUser' => $idUser]);
            
        if ($stmt->rowCount() === 0) {
            return null;
        }

        $produits = []; 
        $id = null;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            if ($id === null) {
                $id = $row['id_panier']; 
            }

            $produit = [
            'product' => 
                    new ProductDetail(
                    new Product(
                        $row['id_produit'],
                        $row['designation'],
                        $row['description'],
                        $row['prix'],
                        $row['url_image'],
                        $row['id_categorie']
                    ),
                    new Color(
                        $row["id_couleur"],
                        $row["couleur_lib"],
                        $row["reduc_couleur"],
                        $row["url_image"] 
                    ),
                    new Size(
                        $row["id_taille"],
                        $row["taille_lib"],
                        $row["reduc_taille"]
                    )),
                'quantity' => $row['qte']
            ];

            $produits[] = $produit;
        }

        return new Cart($id, $idUser, $produits);
    }

    public function addProduct($idUser, $idProduct, $quantity, $idColor, $idSize): void {
        $stmt = $this->pdo->prepare(
            'INSERT INTO CONTENU_PANIER (id_panier, id_produit, qte, id_taille, id_couleur)
            SELECT p.id_panier, :idProduct, :quantity , :idColor, :idSize
            FROM PANIER p
            WHERE p.id_utilisateur = :idUser
            ON DUPLICATE KEY UPDATE
                qte = VALUES(qte);
            ');

        $stmt->execute([
            'idUser' => $idUser,
            'idProduct' => $idProduct,
            'quantity' => $quantity,
            'idColor' => $idColor,
            'idSize' => $idSize
        ]);
    }

    public function removeProduct($idUser, $idProduct, $idColor, $idSize): void {
        $stmt = $this->pdo->prepare(
            'DELETE FROM CONTENU_PANIER
            WHERE id_panier = (SELECT id_panier FROM PANIER WHERE id_utilisateur = :idUser)
            AND id_produit = :idProduct
            AND id_couleur = :idColor
            AND id_taille = :idSize;'
        );

        $stmt->execute([
            'idUser' => $idUser,
            'idProduct' => $idProduct,
            'idColor' => $idColor,
            'idSize' => $idSize
        ]);
    }

    public function removeAllProduct($idUser): void {
        $stmt = $this->pdo->prepare(
            'DELETE FROM CONTENU_PANIER
            WHERE id_panier = (SELECT id_panier FROM PANIER WHERE id_utilisateur = :idUser);'
        );

        $stmt->execute(['idUser' => $idUser]);
    }
}
?>
