<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/Product.php');
require_once('./app/entity/Size.php');
require_once('./app/entity/Color.php');

class ProductDAO
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllProducts(): array {
        $stmt = $this->pdo->prepare('
            SELECT PRODUIT.id_produit, PRODUIT.designation, PRODUIT.description, PRODUIT.prix, PRODUIT.id_categorie,
            COULEUR_PRODUIT.id_couleur, COULEUR.libelle as couleur_lib, COULEUR_PRODUIT.reduction as reduc_couleur,
            COULEUR_PRODUIT.url_image, TAILLE_PRODUIT.id_taille, TAILLE.libelle as taille_lib,
            TAILLE_PRODUIT.reduction as reduc_taille
            FROM PRODUIT, COULEUR_PRODUIT, COULEUR, TAILLE_PRODUIT, TAILLE
            where COULEUR_PRODUIT.id_produit = PRODUIT.id_produit
            AND COULEUR.id_couleur = COULEUR_PRODUIT.id_couleur
            AND TAILLE_PRODUIT.id_produit = PRODUIT.id_produit
            AND TAILLE.id_taille = TAILLE_PRODUIT.id_taille
            GROUP BY PRODUIT.id_produit;'
        );
        $stmt->execute();

        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product = new ProductDetail(
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
                        ));
            $products[] = $product;
        }

        return $products;
    }

    public function searchProducts($search, $color, $size, $category): array
    {
        $sql = '
            SELECT 
                PRODUIT.id_produit,
                PRODUIT.designation,
                PRODUIT.description,
                PRODUIT.prix,
                PRODUIT.id_categorie,
                COULEUR_PRODUIT.id_couleur,
                COULEUR.libelle AS couleur_lib,
                COULEUR_PRODUIT.reduction AS reduc_couleur,
                COULEUR_PRODUIT.url_image,
                TAILLE_PRODUIT.id_taille,
                TAILLE.libelle AS taille_lib,
                TAILLE_PRODUIT.reduction AS reduc_taille
            FROM PRODUIT
            JOIN CATEGORIE ON PRODUIT.id_categorie = CATEGORIE.id_categorie
            JOIN COULEUR_PRODUIT ON COULEUR_PRODUIT.id_produit = PRODUIT.id_produit
            JOIN COULEUR ON COULEUR_PRODUIT.id_couleur = COULEUR.id_couleur
            LEFT JOIN TAILLE_PRODUIT ON TAILLE_PRODUIT.id_produit = PRODUIT.id_produit
            LEFT JOIN TAILLE ON TAILLE.id_taille = TAILLE_PRODUIT.id_taille
        ';

        $params = [];
        $conditions = [];

        if ($search) {
            $conditions[] = "CONCAT(PRODUIT.designation, ' ', PRODUIT.description, ' ', CATEGORIE.libelle) LIKE :search";
            $params[':search'] = "%$search%";
        }

        if ($color) {
            $conditions[] = "COULEUR.id_couleur = :couleur";
            $params[':couleur'] = $color;
        }

        if ($size && $category !== "Bonnet") {
            $conditions[] = "TAILLE.id_taille = :taille";
            $params[':taille'] = $size;
        }

        if ($category) {
            $conditions[] = "CATEGORIE.id_categorie = :categorie";
            $params[':categorie'] = $category;
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= ' GROUP BY PRODUIT.id_produit';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $products = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product = new ProductDetail(
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
                    $row['id_taille'] ?? null,
                    $row['taille_lib'] ?? null,
                    $row['reduc_taille'] ?? null
                )
            );

            $products[] = $product;
        }

        return $products;
    }

    public function getProductByIdAndColorAndSize(int $id, int $color, int $size): ?Product {
        $stmt = $this->pdo->prepare(
            'SELECT PRODUIT.*,
                COULEUR_PRODUIT.reduction AS reduction_couleur, 
                COULEUR_PRODUIT.url_image,
                TAILLE_PRODUIT.reduction AS reduction_taille
            FROM PRODUIT
            JOIN COULEUR_PRODUIT ON COULEUR_PRODUIT.id_produit = PRODUIT.id_produit 
            JOIN TAILLE_PRODUIT ON TAILLE_PRODUIT.id_produit = PRODUIT.id_produit
            WHERE PRODUIT.id_produit = :id 
            AND COULEUR_PRODUIT.id_couleur = :couleur 
            AND TAILLE_PRODUIT.id_taille = :taille
            GROUP BY PRODUIT.id_produit'
        );
        $stmt->execute([
            'id' => $id,
            'couleur' => $color,
            'taille' => $size
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        $reductionCouleur = isset($row['reduction_couleur']) ? (float) $row['reduction_couleur'] : 0;
        $reductionTaille = isset($row['reduction_taille']) ? (float) $row['reduction_taille'] : 0;
        $prixFinal = max(0, $row['prix'] - ($reductionCouleur + $reductionTaille)); // Évite un prix négatif

        $product = new Product(
            $row['id_produit'],
            $row['designation'],
            $row['description'],
            $prixFinal,
            $row['url_image'],
            $row['id_categorie']
        );

        return $product;
    }
}
