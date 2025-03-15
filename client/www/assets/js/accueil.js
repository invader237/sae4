import { getAllProducts } from "./core/api/api.js";

function afficherProduits() {
    getAllProducts().then((response) => {
        const products = response.data;
        const productsContainer = document.getElementById("productsContainer");

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
    }).catch((error) => console.error(error));
}

afficherProduits();
