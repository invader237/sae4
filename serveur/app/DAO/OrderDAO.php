<?php
require_once('./app/core/Connexion.php');
require_once('./app/entity/Order.php');

class OrderDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
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
