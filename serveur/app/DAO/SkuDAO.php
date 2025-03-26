<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/Sku.php');

class SkuDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getSkusByColorAndSize($id, $idColor, $idSize ) {
        $stmt = $this->pdo->prepare('
            SELECT * FROM SKU
            WHERE id_produit = :id
            AND id_taille_produit = :idSize
            AND id_couleur_produit = :idColor
        ');
        $stmt->execute(['id' => $id, 'idColor' => $idColor, 'idSize' => $idSize]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $sku = new Sku(
            $row['sku'],
            $row['stock'],
        );

        return $sku;
    }
}
