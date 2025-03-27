<?php
require_once('./app/entity/Favorite.php');
require_once ('./app/entity/Product.php');
require_once ('./app/entity/Color.php');
require_once ('./app/entity/Size.php');
require_once ('./app/entity/ProductDetail.php');


class FavoriteDAO{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getFavorites($idUser){
        $stmt = $this->pdo->prepare(
            'SELECT 
            p.id_produit, p.designation, p.description, p.prix, 
            cp.url_image, 
            p.id_categorie,
            c.id_couleur, c.libelle AS couleur_lib, 
            cp.reduction AS reduc_couleur,
            t.id_taille, t.libelle AS taille_lib, 
            tp.reduction AS reduc_taille
        FROM FAVORIS f
        JOIN PRODUIT p ON f.id_produit = p.id_produit
        JOIN COULEUR c ON f.id_couleur = c.id_couleur
        JOIN COULEUR_PRODUIT cp ON p.id_produit = cp.id_produit AND f.id_couleur = cp.id_couleur
        JOIN TAILLE t ON f.id_taille = t.id_taille
        JOIN TAILLE_PRODUIT tp ON p.id_produit = tp.id_produit AND f.id_taille = tp.id_taille
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

    public function addFavorites($idUser,$idProduct,$idSize,$idColor){
        $stmt = $this->pdo->prepare(           
           'INSERT INTO FAVORIS (id_utilisateur, id_produit, id_taille, id_couleur) 
            VALUES (:idUser, :idProduct, :idSize, :idColor)'
        );

        $stmt->execute([
            'idUser' => $idUser,
            'idProduct' => $idProduct,
            'idColor' => $idColor,
            'idSize' => $idSize
        ]);
    }

    public function removeFavorites($idUser, $idProduct, $idSize, $idColor): void {
        $stmt = $this->pdo->prepare(
            'DELETE FROM FAVORIS
            WHERE id_utilisateur = :idUser
            AND id_produit = :idProduct
            AND id_taille = :idSize
            AND id_couleur = :idColor;'
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
            'DELETE FROM FAVORIS
            WHERE id_utilisateur = :idUser;'
        );

        $stmt->execute(['idUser' => $idUser]);
    }

}
?>