import { getAllProducts, getAllProductsFilter } from "./core/api/api.js";

const form = document.querySelector("#searchForm");
const productsContainer = document.getElementById("productsContainer");

// Fonction pour afficher les produits
function afficherProduits(products) {
    productsContainer.innerHTML = ""; // Vider le conteneur avant d'ajouter de nouveaux produits

    products.forEach((product) => {
        const productDiv = document.createElement("div");
        productDiv.classList.add("col-lg-3", "col-md-4", "col-sm-6", "col-12");

        const productCard = document.createElement("div");
        productCard.classList.add("card", "p-3", "shadow-sm", "m-1", "h-100", "d-flex", "flex-column");
        product.url_image = product.url_image.replace(" ", "%20");

        productCard.innerHTML = `
            <img src="https://gitlab.univ-lorraine.fr/laroche5/sae401_2425/-/raw/master/serveur/img/articles/${product.url_image}?ref_type=heads" 
                class="card-img-top w-100" 
                alt="Image produit" 
                style="height: 250px; object-fit: cover;">
            <div class="card-body flex-grow-1 d-flex flex-column">
                <h5 class="card-title">${product.designation}</h5>
                <p class="card-text">${product.prix} €</p>
                <a href="/pages/produit.html?id=${product.id_produit}" class="btn btn-primary mt-auto">Voir le produit</a>
            </div>
        `;
        productDiv.appendChild(productCard);
        productsContainer.appendChild(productDiv);
    });
}

// Charger tous les produits au chargement de la page
getAllProducts()
    .then((response) => afficherProduits(response.data))
    .catch((error) => console.error("Erreur lors du chargement des produits:", error));

// Écouter l'événement submit du formulaire
form.addEventListener("submit", (event) => {
    event.preventDefault();
    
    const search = document.getElementById("search").value;
    const category = document.getElementById("category").value;
    const color = document.getElementById("color").value;
    const size = document.getElementById("size").value;

    getAllProductsFilter(search, category, color, size)
        .then((response) => afficherProduits(response.data))
        .catch((error) => console.error("Erreur lors de la récupération des produits filtrés:", error));
});
