import { addToCart, getUser, removeAllFavorites, getFavorites } from "./core/api/api.js";
import { favoritesManager } from "./favorites.js"

const response = await getUser();

if (response === undefined) {
    window.location.href = "./login.html";
}

const clearButton = document.getElementById("clear");
clearButton.addEventListener("click", async (event) => {
    event.preventDefault();
    if (user !== undefined) {
        try {
            removeAllFavorites();
            getFavorites()
                .then((response) => displayProducts(response.data))
                .catch((error) => console.error("Error loading products:", error));
        } catch (error) {
            console.error("Erreur lors de l'ajout au panier :", error);
        }
    } else {
        window.location.href = "./login.html";
    }
});

function displayProducts(response) {
    const products = response.data.favoris; // Accédez à 'favoris' depuis 'data'
    favoritesManager.load();
    const productsContainer = document.getElementById("productsContainer");
    productsContainer.className = "row g-3"
    productsContainer.innerHTML = "";

    if (products.length === 0) {
        console.log("Aucun produit favori trouvé.");
        productsContainer.innerHTML = `
            <div class="alert alert-warning text-center fw-bold">
                Vous n'avez aucun produit favori.
            </div>
        `;
        return;
    }

    products.forEach((item) => {
        const productContent = item.product.product || {};
        const color = item.product.color || {};
        const size = item.product.size || {};

        if (!productContent.id || !color.id || !size.id) {
            console.error("Les informations du produit, de la couleur ou de la taille sont manquantes.");
            return; // Ignore le produit s'il manque des informations essentielles
        }

        const productCardContainer = document.createElement("div");
        productCardContainer.classList.add("col-12", "col-sm-6", "col-md-4", "col-lg-3"); // ou p-2, ou w-100, selon ton design

        const productLink = document.createElement("a");
        productLink.href = `../pages/produit.html?id=${productContent.id}`;
        productLink.classList.add("text-decoration-none", "text-dark", "h-100");

        const productCard = document.createElement("div");
        productCard.classList.add("card", "p-3", "shadow-sm", "m-1", "h-100", "d-flex", "flex-column");

        const imageUrl = productContent.urlImage 
            ? productContent.urlImage.replace(/ /g, "%20")
            : "placeholder.jpg";

        productCard.innerHTML = `
            <img src="https://gitlab.univ-lorraine.fr/laroche5/sae401_2425/-/raw/master/serveur/img/articles/${imageUrl}?ref_type=heads" 
                class="card-img-top w-100" 
                alt="${productContent.designation || 'Image produit'}" 
                style="height: 250px; object-fit: cover;">
            <div class="card-body flex-grow-1 d-flex flex-column">
                <h5 class="card-title">${productContent.label || 'Produit'}</h5>
                <p class="card-text"> ${(Number(productContent.price) || 0).toFixed(2)} €</p>
                <div class="d-flex justify-content-end mt-auto">
                    <button class= "btn btn-success add-to-fav-btn" 
                        data-product-id="${productContent.id}" 
                        data-size-id="${size.id}" 
                        data-color-id="${color.id}">
                        <img src="../assets/img/icones/star_vide.png" style="width: 20px; height: 20px; margin: 5px;" alt="Ajouter aux favoris">
                    </button>
                    <button class="btn btn-success add-to-cart-btn">
                        <img src="../assets/img/icones/shopping-cart.png" style="width: 20px; height: 20px; margin: 5px;" alt="Ajouter au panier">
                    </button>
                </div>
            </div>
        `;

        const addToCartButton = productCard.querySelector(".add-to-cart-btn");
        addToCartButton.addEventListener("click", async (event) => {
            event.preventDefault();
            const user = await getUser();
            if (user !== undefined) {
                try {
                    await addToCart(productContent.id, 1, size.id, color.id);
                    alert("Produit ajouté au panier !");
                } catch (error) {
                    console.error("Erreur lors de l'ajout au panier :", error);
                }
            } else {
                window.location.href = "./login.html";
            }
        });

        const addtoFavButton = productCard.querySelector(".add-to-fav-btn");
        addtoFavButton.addEventListener("click", async (event)=> {
            event.preventDefault();
            const user = await getUser();
            if (user !== undefined) {
                try {
                    favoritesManager.toggle(productContent, size, color, addtoFavButton);
                    getFavorites()
                        .then((response) => displayProducts(response))  // Pass the whole response object
                        .catch((error) => console.error("Error loading products:", error));
                    // Supprime la carte du produit du DOM
                    addtoFavButton.closest(".col-lg-3").remove();
                } catch (error) {
                    console.error("Erreur lors de l'ajout comme favori : ", error);
                }
            } else {
                window.location.href="./login.html";
            }
        });

        productLink.appendChild(productCard);
        productCardContainer.appendChild(productLink);
        productsContainer.appendChild(productCardContainer);
    });
}

getFavorites()
    .then((response) => displayProducts(response))  // Pass the whole response object
    .catch((error) => console.error("Error loading products:", error));
