import { getFavorites, addFavorites, removeFavorites } from "./core/api/api.js";

class Favorites {
    constructor() {
        this.favorites = new Set();
    }

    async load() {
        try {
            const response = await getFavorites(); // Récupère les favoris
            const data = response.data;  // Extraire les données de la réponse
            console.log(data);
            data.favoris.forEach(prod => this.favorites.add(`${prod.product.product.id}-${prod.product.size.id}-${prod.product.color.id}`))
            this.updateFavoriteButtons();
        } catch (error) {
            console.error("Erreur lors du chargement des favoris:", error);
            this.favorites = new Set();
        }
    }

    updateFavoriteButtons() {
        // Trouve tous les boutons d'étoile sur la page et met à jour leur état
        document.querySelectorAll('.favorite-button').forEach(button => {
            const productId = button.dataset.productId;
            const sizeId = button.dataset.sizeId;
            const colorId = button.dataset.colorId;

            const productKey = `${productId}-${sizeId}-${colorId}`;
            const isFavorite = this.favorites.has(productKey);

            this.updateButton(button, isFavorite);
        });
    }

    async toggle(product, size, color, button) {
        await this.load();
        const productKey = `${product.id}-${size.id}-${color.id}`;
        if (this.favorites.has(productKey)) {
            await this.remove(product, size, color, button);
        } else {
            await this.add(product, size, color, button);
        }
    }

    async add(product, size, color, button) {
        try {
            await addFavorites(product.id, size.id, color.id);
            this.favorites.add(`${product.id}-${size.id}-${color.id}`);
            this.updateButton(button, true);
        } catch (error) {
            console.error("Erreur lors de l'ajout aux favoris:", error);
        }
    }

    async remove(product, size, color, button) {
        try {
            await removeFavorites(product.id, size.id, color.id);
            this.favorites.delete(`${product.id}-${size.id}-${color.id}`);
            this.updateButton(button, false);
        } catch (error) {
            console.error("Erreur lors de la suppression des favoris:", error);
        }
    }

    updateButton(button, isFavorite) {
        const img = button.querySelector("img");
        img.src = isFavorite ? "../assets/img/icones/star_plein.png" : "../assets/img/icones/star_vide.png";
        
    }
}

export const favoritesManager = new Favorites();
favoritesManager.load();
