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
}
