<?php
require_once './app/entity/Panier.php';

class PanierDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getPanier($id_utilisateur) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM `PANIER`, CONTENU_PANIER, PRODUIT
            WHERE PANIER.id_produit = PRODUIT.id_produit
            and PANIER.id_produit = CONTENU_PANIER.id_produit
            and PANIER.id_utilisateur = :id_utilisateur"
        );
        $stmt->execute(['id_utilisateur' => $id_utilisateur]);
        $panier = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product = new Product(
                $row['id_produit'],
                $row['designation'],
                $row['description'],
                $row['prix'],
                $row['url_image'],
                $row['id_categorie']
            );
            $panier[] = [
                'produit' => $product,
                'qte' => $row['qte'],
                'id_taille' => $row['id_taille'],
                'id_couleur' => $row['id_couleur']
            ];
        }

        return $panier;
    }

    // Ajout ou mise à jour d'un panier
    public function addProduitPanier(int $id_utilisateur,int $id_produit, int $qte,int $id_taille,int $id_couleur) {
        // Vérifier si un panier existe déjà pour l'utilisateur
        $stmt = $this->pdo->prepare("SELECT id_panier FROM PANIER WHERE id_utilisateur = :id_utilisateur");
        $stmt->execute(['id_utilisateur' => $id_utilisateur]);
        $panier = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$panier) {
            // Création de panier si l'utilisateur n'en a pas
            $stmt = $this->pdo->prepare("INSERT INTO PANIER (id_utilisateur) VALUES (:id_utilisateur)");
            $stmt->execute(['id_utilisateur' => $id_utilisateur]);
            $id_panier = $this->pdo->lastInsertId();
        } else {
            $id_panier = $panier['id_panier'];
        }

        // Vérification de présence du produit dans le panier
        $stmt = $this->pdo->prepare("SELECT qte FROM CONTENU_PANIER WHERE id_panier = :id_panier AND id_produit = :id_produit AND id_taille = :id_taille AND id_couleur = :id_couleur");
        $stmt->execute([
            'id_panier' => $id_panier,
            'id_produit' => $id_produit,
            'id_taille' => $id_taille,
            'id_couleur' => $id_couleur
        ]);
        $contenu = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($contenu) {
            // Mise à jour de la quantité si le produit est déjà dans le panier
            $nouvelle_qte = $contenu['qte'] + $qte;
            $stmt = $this->pdo->prepare("UPDATE CONTENU_PANIER SET qte = :qte WHERE id_panier = :id_panier AND id_produit = :id_produit AND id_taille = :id_taille AND id_couleur = :id_couleur");
            return $stmt->execute([
                'qte' => $nouvelle_qte,
                'id_panier' => $id_panier,
                'id_produit' => $id_produit,
                'id_taille' => $id_taille,
                'id_couleur' => $id_couleur
            ]);
        } else {
            // Sinon ajout d'un nouveau produit au panier
            $stmt = $this->pdo->prepare("INSERT INTO CONTENU_PANIER (id_panier, id_produit, qte, id_taille, id_couleur) VALUES (:id_panier, :id_produit, :qte, :id_taille, :id_couleur)");
            return $stmt->execute([
                'id_panier' => $id_panier,
                'id_produit' => $id_produit,
                'qte' => $qte,
                'id_taille' => $id_taille,
                'id_couleur' => $id_couleur
            ]);
        }
    }

    // Modification de la quantité d'un produit dans le panier
    public function updateQuantiteProduit($id_utilisateur, $id_produit, $qte, $id_taille, $id_couleur) {
        $stmt = $this->pdo->prepare(
            "UPDATE CONTENU_PANIER cp
            JOIN PANIER pa ON cp.id_panier = pa.id_panier
            SET cp.qte = :qte
            WHERE pa.id_utilisateur = :id_utilisateur AND cp.id_produit = :id_produit AND cp.id_taille = :id_taille AND cp.id_couleur = :id_couleur");
        return $stmt->execute([
            'qte' => $qte,
            'id_utilisateur' => $id_utilisateur,
            'id_produit' => $id_produit,
            'id_taille' => $id_taille,
            'id_couleur' => $id_couleur
        ]);
    }

    // Suppression d'un produit du panier
    public function deleteProduitPanier($id_utilisateur, $id_produit, $id_taille, $id_couleur) {
        $stmt = $this->pdo->prepare(
            "DELETE cp FROM CONTENU_PANIER cp
            JOIN PANIER pa ON cp.id_panier = pa.id_panier
            WHERE pa.id_utilisateur = :id_utilisateur AND cp.id_produit = :id_produit AND cp.id_taille = :id_taille AND cp.id_couleur = :id_couleur"
        );
        return $stmt->execute([
            'id_utilisateur' => $id_utilisateur,
            'id_produit' => $id_produit,
            'id_taille' => $id_taille,
            'id_couleur' => $id_couleur
        ]);
    }

    // Vidage du panier d'un utilisateur
    public function clearPanier($id_utilisateur) {
        $stmt = $this->pdo->prepare(
            "DELETE cp FROM CONTENU_PANIER cp
            JOIN PANIER pa ON cp.id_panier = pa.id_panier
            WHERE pa.id_utilisateur = :id_utilisateur"
        );
        return $stmt->execute(['id_utilisateur' => $id_utilisateur]);
    }
}
?>
