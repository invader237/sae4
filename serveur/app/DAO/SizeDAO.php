<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/Size.php');

class SizeDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getSizesByProductId(int $id): array {
        $stmt = $this->pdo->prepare('
            SELECT TAILLE.id_taille, TAILLE.libelle 
            FROM TAILLE
            JOIN TAILLE_PRODUIT ON TAILLE.id_taille = TAILLE_PRODUIT.id_taille 
            WHERE TAILLE_PRODUIT.id_produit = :id
        ');
        $stmt->execute(['id' => $id]);

        $sizes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sizes[] = new Size($row['id_taille'], $row['libelle'], -1);
        }

        return $sizes;
    }
}
