import { getFavorites, addFavorites, removeFavorites } from "./core/api/api.js";

class Favorites {
    constructor() {
        this.favorites = new Set();
    }

    async load() {
        try {
            const response = await getFavorites(); // Récupère les favoris
            const data = response.data;  // Extraire les données de la réponse
            console.log(data)
            data.favoris.forEach(prod => this.favorites.add(`${prod.product.product.id}-${prod.product.size.id}-${prod.product.color.id}`))
            this.updateFavoriteButtons();
        } catch (error) {
            console.error("Erreur lors du chargement des favoris:", error);
            this.favorites = new Set();
        }
    }

    updateFavoriteButtons() {
        // Trouve tous les boutons d'étoile sur la page et met à jour leur état
        document.querySelectorAll('.add-to-fav-btn').forEach(button => {
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

    async loadDetails() {
        try {
            const response = await getFavorites(); // Récupère les favoris
            const data = response.data;  // Extraire les données de la réponse
            data.favoris.forEach(prod => this.favorites.add(`${prod.product.product.id}-${prod.product.size.id}-${prod.product.color.id}`))
            this.updateFavoriteButtonsDetails();
        } catch (error) {
            console.error("Erreur lors du chargement des favoris:", error);
            this.favorites = new Set();
        }
    }

    async toggleDetails(idProduct, idSize, idColor, button) {
        await this.loadDetails();
        const productKey = `${idProduct}-${idSize}-${idColor}`;
        if (this.favorites.has(productKey)) {
            await this.removeDetails(idProduct, idSize, idColor, button);
        } else {
            await this.addDetails(idProduct, idSize, idColor, button);
        }
    }

    async addDetails(idProduct, idSize, idColor, button) {
        try {
            await addFavorites(idProduct, idSize, idColor);
            this.favorites.add(`${idProduct}-${idSize}-${idColor}`);
            this.updateButton(button, true);
        } catch (error) {
            console.error("Erreur lors de l'ajout aux favoris:", error);
        }
    } 

    async removeDetails(idProduct, idSize, idColor, button) {
        try {
            await removeFavorites(idProduct, idSize, idColor);
            this.favorites.delete(`${idProduct}-${idSize}-${idColor}`);
            this.updateButton(button, false);
        } catch (error) {
            console.error("Erreur lors de la suppression des favoris:", error);
        }
    }

    updateFavoriteButtonsDetails() {
        // Trouve tous les boutons d'étoile sur la page et met à jour leur état
        document.querySelectorAll('.add-to-fav-btn').forEach(button => {
            const productId = button.data_product_id;
            const sizeId = button.data_size_id;
            const colorId = button.data_color_id;

            const productKey = `${productId}-${sizeId}-${colorId}`;
            const isFavorite = this.favorites.has(productKey);

            this.updateButton(button, isFavorite);
        });
    }
    updateButton(button, isFavorite) {
        const img = button.querySelector("img");
        img.src = isFavorite ? "../assets/img/icones/star_plein.png" : "../assets/img/icones/star_vide.png";
        
    }
}

export const favoritesManager = new Favorites();
