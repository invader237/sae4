<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/Product.php');

class ProductDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllProducts(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM PRODUIT, COULEUR_PRODUIT where COULEUR_PRODUIT.id_produit = PRODUIT.id_produit GROUP BY PRODUIT.id_produit;');
        $stmt->execute();

        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = new Product($row['id_produit'], $row['designation'], $row['description'], $row['prix'], $row['url_image'], $row['id_categorie']);
        }
        
        return $products;
    }

    public function searchProducts($search, $color, $size, $category): array {
        $sql = "SELECT * FROM PRODUIT 
                JOIN CATEGORIE ON PRODUIT.id_categorie = CATEGORIE.id_categorie";

        $params = []; 

        $sql .= " JOIN COULEUR_PRODUIT ON COULEUR_PRODUIT.id_produit = PRODUIT.id_produit
                  JOIN COULEUR ON COULEUR_PRODUIT.id_couleur = COULEUR.id_couleur";

        if ($size !== null) {
            $sql .= " JOIN TAILLE_PRODUIT ON TAILLE_PRODUIT.id_produit = PRODUIT.id_produit
                      JOIN TAILLE ON TAILLE.id_taille = TAILLE_PRODUIT.id_taille";
        }

        $conditions = [];
        
        if ($search !== null) {
            $conditions[] = "CONCAT(designation, ' ', description, ' ', CATEGORIE.libelle) LIKE :search";
            $params[':search'] = "%$search%";
        }

        if ($color !== null) {
            $conditions[] = "COULEUR.libelle = :couleur";
            $params[':couleur'] = $color;
        }

        if ($size !== null && $category !== "Bonnet") {
            $conditions[] = "TAILLE.libelle = :taille";
            $params[':taille'] = $size;
        }

        if ($category !== null) {
            $conditions[] = "CATEGORIE.libelle = :categorie";
            $params[':categorie'] = $category;
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " GROUP BY PRODUIT.id_produit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = new Product(
                $row['id_produit'], $row['designation'], $row['description'], $row['prix'], $row['url_image'], $row['id_categorie']
            );
        }

        return $products;
    }
}
