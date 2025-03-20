<?php
require_once './app/entity/Panier.php';
require_once './app/entity/Product.php';

class PanierDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getPanier($id_utilisateur) {
        $stmt = $this->pdo->prepare(
            "SELECT * 
            FROM PANIER, CONTENU_PANIER, PRODUIT, COULEUR, TAILLE, COULEUR_PRODUIT
            where CONTENU_PANIER.id_produit = PRODUIT.id_produit
            AND CONTENU_PANIER.id_couleur = COULEUR.id_couleur
            AND CONTENU_PANIER.id_taille = TAILLE.id_taille
            AND COULEUR_PRODUIT.id_couleur = CONTENU_PANIER.id_couleur
            AND COULEUR_PRODUIT.id_produit = CONTENU_PANIER.id_produit
            AND PANIER.id_panier = CONTENU_PANIER.id_panier
            AND PANIER.id_utilisateur = :id_utilisateur;"
        );
            $stmt->execute(['id_utilisateur' => $id_utilisateur]);
            
            if ($stmt->rowCount() === 0) {
                return null;
            }

            $produits = []; 
            $id_panier = null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                if ($id_panier === null) {
                    $id_panier = $row['id_panier']; 
                }

                $produit = [
                    'product' => new Product(
                        $row['id_produit'],
                        $row['designation'],
                        $row['description'],
                        $row['prix'],
                        $row['url_image'],
                        $row['id_categorie'],
                        $row['id_couleur'],
                        $row['id_taille']
                    ),
                    'quantity' => $row['qte']
                ];

                $produits[] = $produit;
            }

            return new Panier($id_panier, $id_utilisateur, $produits);
    }

    public function addProduit($id_utilisateur, $produitId, $quantite, $id_couleur, $id_taille) {
        try {
            // Vérifier si un panier existe pour l'utilisateur
            $stmt = $this->pdo->prepare("SELECT id_panier FROM PANIER WHERE id_utilisateur = :id_utilisateur;");
            $stmt->execute(['id_utilisateur' => $id_utilisateur]);
            $panier = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($panier) {
                $id_panier = $panier['id_panier'];
            } else {
                // Créer un nouveau panier pour l'utilisateur
                $stmt = $this->pdo->prepare("INSERT INTO PANIER (id_utilisateur) VALUES (:id_utilisateur);");
                $stmt->execute(['id_utilisateur' => $id_utilisateur]);
                $id_panier = $this->pdo->lastInsertId();
            }

            // Vérifier si le produit est déjà dans le panier
            $stmt = $this->pdo->prepare("
                SELECT qte FROM CONTENU_PANIER 
                WHERE id_panier = :id_panier AND id_produit = :id_produit 
                AND id_couleur = :id_couleur AND id_taille = :id_taille;
            ");
            $stmt->execute([
                'id_panier' => $id_panier,
                'id_produit' => $produitId,
                'id_couleur' => $id_couleur,
                'id_taille' => $id_taille
            ]);
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($produit) {
                // Mettre à jour la quantité si le produit est déjà présent
                $newQuantite = $produit['qte'] + $quantite;
                $stmt = $this->pdo->prepare("
                    UPDATE CONTENU_PANIER 
                    SET qte = :qte 
                    WHERE id_panier = :id_panier AND id_produit = :id_produit 
                    AND id_couleur = :id_couleur AND id_taille = :id_taille;
                ");
                $stmt->execute([
                    'qte' => $newQuantite,
                    'id_panier' => $id_panier,
                    'id_produit' => $produitId,
                    'id_couleur' => $id_couleur,
                    'id_taille' => $id_taille
                ]);
            } else {
                // Ajouter un nouveau produit dans le panier
                $stmt = $this->pdo->prepare("
                    INSERT INTO CONTENU_PANIER (id_panier, id_produit, id_couleur, id_taille, qte) 
                    VALUES (:id_panier, :id_produit, :id_couleur, :id_taille, :qte);
                ");
                $stmt->execute([
                    'id_panier' => $id_panier,
                    'id_produit' => $produitId,
                    'id_couleur' => $id_couleur,
                    'id_taille' => $id_taille,
                    'qte' => $quantite
                ]);
            }

            return $this->getPanier($id_utilisateur);
        } catch (Exception $e) {
            return "Erreur : " . $e->getMessage();
        }
    }
}
?>
