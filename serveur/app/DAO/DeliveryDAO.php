<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/Delivery.php');

class DeliveryDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array {
        $stmt = $this->pdo->prepare(' SELECT * FROM LIVRAISON ');
        $stmt->execute();

        $deliveries = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $delivery = new Delivery(
                $row['id_livraison'],
                $row['libelle'],
                $row['prix_livraison']
            );
            $deliveries[] = $delivery;
        }

        return $deliveries;
    }
}
