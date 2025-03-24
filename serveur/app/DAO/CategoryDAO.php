<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/Category.php');

class CategoryDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array {
        $stmt = $this->pdo->prepare(' SELECT * FROM CATEGORIE ');
        $stmt->execute();

        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $category = new Category(
                $row['id_categorie'],
                $row['libelle']
            );
            $categories[] = $category;
        }

        return $categories;
    }

}
