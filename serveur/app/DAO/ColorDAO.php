<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/Color.php');

class ColorDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllColors(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM COULEUR');
        $stmt->execute();

        $colors = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $colors[] = new Color($row['id_couleur'], $row['libelle']);
        }
        
        return $colors;
    }

    public function getColorById(int $id): ?Color {
        $stmt = $this->pdo->prepare('SELECT * FROM COULEUR WHERE id_couleur = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        return new Color($row['id_couleur'], $row['libelle']);
    }
}