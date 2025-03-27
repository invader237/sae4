import { getAllProducts, getAllProductsFilter, addToCart, getAllSizes, getAllColors, getAllCategorys, getUser } from "./core/api/api.js";
import {favoritesManager} from "./favorites.js"


const searchForm = document.querySelector("#searchForm");
const productsContainer = document.getElementById("productsContainer");

function displayProducts(products) {
    const productsContainer = document.getElementById("productsContainer");
    productsContainer.innerHTML = "";

    if (products.length === 0) {
        console.log("Aucun produit trouvé.");
        productsContainer.innerHTML = `
            <div class="alert alert-warning text-center fw-bold">
                Aucun produit ne correspond à votre recherche.
            </div>
        `;
        return;
    }

    products.forEach((product) => {
        const productContent = product.product;
        const color = product.color;
        const size = product.size;

        const productCardContainer = document.createElement("div");
        productCardContainer.classList.add("col-lg-3", "col-md-4", "col-sm-6", "col-12");

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
                    <button class= "btn btn-success add-to-fav-btn">
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
            if (user !==undefined) {
                try {
                    favoritesManager.toggle(productContent, size, color, addtoFavButton);
                } catch (error) {
                    console.error("Erreur lors de l'ajout comme favori : ", error);
                }
            }   else {
                window.location.href="./login.html";
            }
        })

        productLink.appendChild(productCard);
        productCardContainer.appendChild(productLink);
        productsContainer.appendChild(productCardContainer);
    });
}

getAllProducts()
    .then((response) => displayProducts(response.data))
    .catch((error) => console.error("Error loading products:", error));


function loadFilters() {
    const categorySelect = document.getElementById("category");
    const colorSelect = document.getElementById("color");
    const sizeSelect = document.getElementById("size");

    getAllCategorys()
        .then((response) => {
            response.data.forEach((category) => {
                const categoryOption = document.createElement("option");
                categoryOption.value = category.id;
                categoryOption.textContent = category.label;
                categorySelect.appendChild(categoryOption);
            });
        })
        .catch((error) => console.error("Erreur lors du chargement des catégories :", error));

    getAllColors()
        .then((response) => {
            response.data.forEach((color) => {
                const colorOption = document.createElement("option");
                colorOption.value = color.id;
                colorOption.textContent = color.label;
                colorSelect.appendChild(colorOption);
            });
        })
        .catch((error) => console.error("Erreur lors du chargement des couleurs :", error));

    getAllSizes()
        .then((response) => {
            response.data.forEach((size) => {
                const sizeOption = document.createElement("option");
                sizeOption.value = size.id;
                sizeOption.textContent = size.label;
                sizeSelect.appendChild(sizeOption);
            });
        })
        .catch((error) => console.error("Erreur lors du chargement des tailles :", error));
}

loadFilters();

searchForm.addEventListener("submit", (event) => {
    event.preventDefault();
    
    const searchQuery = document.getElementById("search").value;
    const category = document.getElementById("category").value;
    const color = document.getElementById("color").value;
    const size = document.getElementById("size").value;

    getAllProductsFilter(searchQuery, category, color, size)
        .then((response) => displayProducts(response.data))
        .catch((error) => console.error("Erreur lors de la recherche de produits :", error));
});
