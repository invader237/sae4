<?php
require_once('./app/entity/Favorite.php');
require_once ('./app/entity/Product.php');
require_once ('./app/entity/Color.php');
require_once ('./app/entity/Size.php');
require_once ('./app/entity/ProductDetail.php')


class FavoriteDAO{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getFavorites($idUser){
        $stmt = $this->pdo->prepare(
            'SELECT 
                p.id_produit, p.designation, p.description, p.prix, p.id_categorie,
                c.id_couleur, c.libelle AS couleur_lib,
                t.id_taille, t.libelle AS taille_lib
            FROM CONTENU_FAVORIS cf
            JOIN FAVORIS f ON cf.id_favoris = f.id_favoris
            JOIN PRODUIT p ON cf.id_produit = p.id_produit
            JOIN COULEUR c ON cf.id_couleur = c.id_couleur
            JOIN TAILLE t ON cf.id_taille = t.id_taille
            WHERE f.id_utilisateur = :idUser;'
        );

        $stmt->execute(['idUser' => $idUser]);

        if ($stmt->rowCount() === 0) {
            return null;
        }

        $favoris = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $favoris[] = new ProductDetail(
                new Product(
                    $row['id_produit'],
                    $row['designation'],
                    $row['description'],
                    $row['prix'],
                    $row['url_image'],
                    $row['id_categorie']
                ),
                new Color(
                    $row['id_couleur'],
                    $row['couleur_lib'],
                    $row['reduc_couleur'],
                    $row['url_image']
                ),
                new Size(
                    $row['id_taille'],
                    $row['taille_lib'],
                    $row['reduc_taille']
                )
            );
        }

        return new Favorite($idUser, $favoris);
    }

    public function addFavorite($idUser,$idProduct,$idSize,$idColor){
        $stmt = $this->pdo->prepare(           
           'INSERT INTO CONTENU_FAVORIS (id_favoris, id_produit, id_taille, id_couleur) 
            SELECT f.id_favoris, :idProduct, :idSize, :idColor
            FROM FAVORIS f
            WHERE f.id_utilisateur = :idUser;'
        );

        $stmt->execute([
            'idUser' => $idUser,
            'idProduct' => $idProduct,
            'quantity' => $quantity,
            'idColor' => $idColor,
            'idSize' => $idSize
        ]);
    }

    public function removeFavorite($idUser, $idProduct, $idColor, $idSize): void {
        $stmt = $this->pdo->prepare(
            'DELETE FROM CONTENU_FAVORIS
            WHERE id_favoris = (SELECT id_favoris FROM FAVORIS WHERE id_utilisateur = :idUser)
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

    public function removeAllFavorites($idUser): void {
        $stmt = $this->pdo->prepare(
            'DELETE FROM CONTENU_FAVORIS
            WHERE id_favoris = (SELECT id_favoris FROM FAVORIS WHERE id_utilisateur = :idUser);'
        );

        $stmt->execute(['idUser' => $idUser]);
    }

}
?>