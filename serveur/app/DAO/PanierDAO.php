<?php
require_once '../entity/Panier.php';

class PanierDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getPanier($id_utilisateur) {
        $sql = "SELECT cp.id_produit, p.designation, p.description, p.prix, p.url_image, p.id_categorie, 
                       cp.qte, cp.id_taille, cp.id_couleur
                FROM PANIER pa
                JOIN CONTENU_PANIER cp ON pa.id_panier = cp.id_panier
                JOIN PRODUIT p ON cp.id_produit = p.id_produit
                WHERE pa.id_utilisateur = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_utilisateur]);
        $panier = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $panier[] = [
                'produit' => new Product(
                    $row['id_produit'],
                    $row['designation'],
                    $row['description'],
                    $row['prix'],
                    $row['url_image'],
                    $row['id_categorie']
                ),
                'qte' => $row['qte'],
                'id_taille' => $row['id_taille'],
                'id_couleur' => $row['id_couleur']
            ];
        }

        return $panier;
    }

    // Ajout ou mise à jour d'un panier
    public function addProduitPanier($id_utilisateur, $id_produit, $qte, $id_taille, $id_couleur) {
        // Vérifier si un panier existe déjà pour l'utilisateur
        $sql = "SELECT id_panier FROM PANIER WHERE id_utilisateur = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_utilisateur]);
        $panier = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$panier) {
            // Création de panier si l'utilisateur n'en a pas
            $sql = "INSERT INTO PANIER (id_utilisateur) VALUES (?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_utilisateur]);
            $id_panier = $this->pdo->lastInsertId();
        } else {
            $id_panier = $panier['id_panier'];
        }

        // Vérification de présence du produit dans le panier
        $sql = "SELECT qte FROM CONTENU_PANIER WHERE id_panier = ? AND id_produit = ? AND id_taille = ? AND id_couleur = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_panier, $id_produit, $id_taille, $id_couleur]);
        $contenu = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($contenu) {
            // Mise à jour de la quantité si le produit est déjà dans le panier
            $nouvelle_qte = $contenu['qte'] + $qte;
            $sql = "UPDATE CONTENU_PANIER SET qte = ? WHERE id_panier = ? AND id_produit = ? AND id_taille = ? AND id_couleur = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nouvelle_qte, $id_panier, $id_produit, $id_taille, $id_couleur]);
        } else {
            // Sinon ajout d'un nouveau produit au panier
            $sql = "INSERT INTO CONTENU_PANIER (id_panier, id_produit, qte, id_taille, id_couleur) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_panier, $id_produit, $qte, $id_taille, $id_couleur]);
        }
    }

    // Modification de la quantité d'un produit dans le panier
    public function updateQuantiteProduit($id_utilisateur, $id_produit, $qte, $id_taille, $id_couleur) {
        $sql = "UPDATE CONTENU_PANIER cp
                JOIN PANIER pa ON cp.id_panier = pa.id_panier
                SET cp.qte = ?
                WHERE pa.id_utilisateur = ? AND cp.id_produit = ? AND cp.id_taille = ? AND cp.id_couleur = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$qte, $id_utilisateur, $id_produit, $id_taille, $id_couleur]);
    }

    // Suppression d'un produit du panier
    public function deleteProduitPanier($id_utilisateur, $id_produit, $id_taille, $id_couleur) {
        $sql = "DELETE cp FROM CONTENU_PANIER cp
                JOIN PANIER pa ON cp.id_panier = pa.id_panier
                WHERE pa.id_utilisateur = ? AND cp.id_produit = ? AND cp.id_taille = ? AND cp.id_couleur = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_utilisateur, $id_produit, $id_taille, $id_couleur]);
    }

    // Vidage du panier d'un utilisateur
    public function clearPanier($id_utilisateur) {
        $sql = "DELETE cp FROM CONTENU_PANIER cp
                JOIN PANIER pa ON cp.id_panier = pa.id_panier
                WHERE pa.id_utilisateur = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_utilisateur]);
    }
}
?>