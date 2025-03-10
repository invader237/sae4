<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/Size.php');

class SizeDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllSizes(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM TAILLE');
        $stmt->execute();

        $sizes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sizes[] = new Size($row['id_taille'], $row['libelle']);
        }
        
        return $sizes;
    }

    public function getSizeById(int $id): ?Size {
        $stmt = $this->pdo->prepare('SELECT * FROM TAILLE WHERE id_taille = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }
        return new Size($row['id_taille'], $row['libelle']);
    }
}