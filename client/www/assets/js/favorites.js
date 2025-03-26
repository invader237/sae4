import { getFavorites, addFavorites, removeFavorites } from "./core/api/api.js";

// Fonction pour charger les favoris et les retourner sous forme de Set
export async function loadFavorites() {
    try {
        const favorites = await getFavorites();  // Récupérer les favoris via l'API
        return new Set(favorites.map(fav => `${fav.idProduct}-${fav.idSize}-${fav.idColor}`));  // Créer un Set pour éviter les doublons
    } catch (error) {
        console.error("Erreur lors du chargement des favoris:", error);
        return new Set();  // Retourner un Set vide en cas d'erreur
    }
}

// Fonction pour gérer l'ajout et la suppression des favoris
export async function handleFavorite(productKey, productContent, size, color, favoriteButton) {
    let favorites = await loadFavorites();  // Charger les favoris actuels

    if (favorites.has(productKey)) {
        // Si déjà favori, retirer
        try {
            await removeFavorites(productContent.id, size.id, color.id);
            favorites.delete(productKey);  // Supprimer de la liste des favoris
            favoriteButton.querySelector("img").src = "../assets/img/icones/star-empty.png";  // Changer l'icône en vide
        } catch (error) {
            console.error("Erreur lors de la suppression des favoris :", error);
        }
    } else {
        // Si non favori, ajouter
        try {
            await addFavorites(productContent.id, size.id, color.id);
            favorites.add(productKey);  // Ajouter à la liste des favoris
            favoriteButton.querySelector("img").src = "../assets/img/icones/star-filled.png";  // Changer l'icône en remplie
        } catch (error) {
            console.error("Erreur lors de l'ajout des favoris :", error);
        }
    }
}
