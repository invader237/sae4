import { getAllProducts, getAllProductsFilter } from "./core/api/api.js";

const searchForm = document.querySelector("#searchForm");
const productsContainer = document.getElementById("productsContainer");

function displayProducts(products) {
    productsContainer.innerHTML = "";

    products.forEach((product) => {
        const productCardContainer = document.createElement("div");
        productCardContainer.classList.add("col-lg-3", "col-md-4", "col-sm-6", "col-12");

        const productCard = document.createElement("div");
        productCard.classList.add("card", "p-3", "shadow-sm", "m-1", "h-100", "d-flex", "flex-column");
        
        const imageUrl = product.urlImage.replace(" ", "%20");
        
        productCard.innerHTML = `
            <img src="https://gitlab.univ-lorraine.fr/laroche5/sae401_2425/-/raw/master/serveur/img/articles/${imageUrl}?ref_type=heads" 
                class="card-img-top w-100" 
                alt="Product Image" 
                style="height: 250px; object-fit: cover;">
            <div class="card-body flex-grow-1 d-flex flex-column">
                <h5 class="card-title">${product.label}</h5>
                <p class="card-text">${product.price} €</p>
                <a href="../pages/produit.html?id=${product.id}" class="btn btn-primary mt-auto">View Product</a>
            </div>
        `;

        productCardContainer.appendChild(productCard);
        productsContainer.appendChild(productCardContainer);
    });
}

getAllProducts()
    .then((response) => displayProducts(response.data))
    .catch((error) => console.error("Error loading products:", error));

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
