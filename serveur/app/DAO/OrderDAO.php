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

    public function getOrderById($idUser, $idOrder) {
        $stmt = $this->pdo->prepare("
            SELECT CONTENU_COMMANDE.*, TAILLE.*, COULEUR.*, PRODUIT.*,
            COULEUR.libelle as couleur_lib, TAILLE.libelle as taille_lib
            FROM COMMANDE, CONTENU_COMMANDE, TAILLE, COULEUR, PRODUIT
            WHERE COMMANDE.id_utilisateur = :idUser
            and COMMANDE.id_commande = :idOrder
            AND COMMANDE.id_commande = CONTENU_COMMANDE.id_commande
            AND CONTENU_COMMANDE.id_produit = PRODUIT.id_produit
            AND CONTENU_COMMANDE.id_taille = TAILLE.id_taille
            AND CONTENU_COMMANDE.id_couleur = COULEUR.id_couleur
            ");
        $stmt->execute(['idUser' => $idUser, 'idOrder' => $idOrder]);

        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product = new ProductDetail(
                new Product(
                    $row['id_produit'],
                    $row['designation'],
                    $row['description'],
                    $row['prix'],
                    "",
                    $row['id_categorie']
                ),
                new Color(
                    $row['id_couleur'],
                    $row['couleur_lib'],
                    "",
                    ""
                ),
                new Size(
                    $row['id_taille'],
                    $row['taille_lib'],
                    ""
                )
            );
            $products[] = ['product' => $product, 'quantity' => $row['qte']];
        }

        $stmt = $this->pdo->prepare("
            SELECT COMMANDE.*, LIVRAISON.libelle as libelle_livraison ,SUM(CONTENU_COMMANDE.qte * CONTENU_COMMANDE.prix_unit) + LIVRAISON.prix_livraison AS total
            FROM COMMANDE
            JOIN CONTENU_COMMANDE ON COMMANDE.id_commande = CONTENU_COMMANDE.id_commande
            JOIN LIVRAISON on COMMANDE.id_livraison = LIVRAISON.id_livraison
            WHERE COMMANDE.id_utilisateur = :idUser
            and COMMANDE.id_commande = :idOrder
            GROUP BY COMMANDE.id_commande;
            ");

        $stmt->execute(['idUser' => $idUser, 'idOrder' => $idOrder]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $order = ['order' => 
            new Order(
            $row['id_commande'],
            $row['id_utilisateur'],
            $row['id_transaction'],
            $row['libelle_livraison'],
            $row['adresse'],
            $row['date_commande'],
            $products
            ), 
            'total' => $row['total']
        ];

        return $order;
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
