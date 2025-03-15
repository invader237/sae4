<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/Color.php');

class ColorDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getColorsByProductId(int $id): array {
        $stmt = $this->pdo->prepare('
            SELECT COULEUR.id_couleur, COULEUR.libelle 
            FROM COULEUR 
            JOIN COULEUR_PRODUIT ON COULEUR.id_couleur = COULEUR_PRODUIT.id_couleur 
            WHERE COULEUR_PRODUIT.id_produit = :id
        ');
        $stmt->execute(['id' => $id]);

        $colors = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $colors[] = new Color($row['id_couleur'], $row['libelle']);
        }

        return $colors;
    }
}