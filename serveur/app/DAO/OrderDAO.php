<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/Order.php');

class OrderDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($idUser) {
        $stmt = $this->pdo->prepare("
            SELECT COMMANDE.*, LIVRAISON.libelle as libelle_livraison ,SUM(CONTENU_COMMANDE.qte * CONTENU_COMMANDE.prix_unit) AS total
            FROM COMMANDE
            JOIN CONTENU_COMMANDE ON COMMANDE.id_commande = CONTENU_COMMANDE.id_commande
            JOIN LIVRAISON on COMMANDE.id_livraison = LIVRAISON.id_livraison
            WHERE COMMANDE.id_utilisateur = :idUser
            GROUP BY COMMANDE.id_commande;
            ");
        $stmt->execute(['idUser' => $idUser]);

        if ($stmt->rowCount() === 0) {
            return null;
        }

        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $order = ['order' => 
                new Order(
                $row['id_commande'],
                $row['id_utilisateur'],
                $row['id_transaction'],
                $row['libelle_livraison'],
                $row['adresse'],
                $row['date_commande'],
                []
                ), 
                'total' => $row['total']
            ];
            $orders[] = $order;
        }

        return $orders;
    }

    public function createOrder(Order $order): Order {
        $stmt = $this->pdo->prepare('INSERT INTO COMMANDE (id_utilisateur, id_transaction, id_livraison, adresse, date_commande) VALUES (:idUser, :idPayment, :idDelivery, :deliveryAddress, :date)');
        $stmt->execute([
            'idUser' => $order->getIdUser(),
            'idPayment' => $order->getIdPayment(),
            'idDelivery' => $order->getIdDelivery(), 
            'deliveryAddress' => $order->getDeliveryAddress(), 
            'date' => $order->getDate()
        ]);

        $idOrder = $this->pdo->lastInsertId();
        $stmt = $this->pdo->prepare('INSERT INTO CONTENU_COMMANDE (id_commande, id_produit, qte, id_couleur, id_taille, prix_unit) VALUES (:idOrder, :idProduct, :quantity, :idColor, :idSize, :price)');
        foreach ($order->getProducts() as $productData) {

            $stmt->execute([
                'idOrder' => $idOrder,
                'idProduct' => $productData['product']->getProduct()->getId(),
                'quantity' => $productData['quantity'],
                'idColor' => $productData['product']->getColor()->getId(),
                'idSize' => $productData['product']->getSize()->getId(),
                'price' => $productData['product']->getProduct()->getPrice()
            ]);
        }

        return new Order($idOrder, $order->getIdUser(), $order->getIdPayment(), $order->getIdDelivery(), $order->getDeliveryAddress(), $order->getDate(), $order->getProducts());
    }

}
