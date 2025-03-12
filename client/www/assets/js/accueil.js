import { getAllProducts } from "./core/api/api.js";

function afficherProduits() {
    getAllProducts().then((response) => {
        const products = response.data;
        const productsContainer = document.getElementById("productsContainer");
        products.forEach((product) => {
            const productCard = document.createElement("div");
            productCard.classList.add("card");
            productCard.style.width = "18rem";
            product.url_image = product.url_image.replace(" ", "%20");
            productCard.innerHTML = `
            <img src="https://gitlab.univ-lorraine.fr/laroche5/sae401_2425/-/raw/master/serveur/img/articles/${product.url_image}?ref_type=heads" class="card-img-top w-100 h-100" alt="..." style="object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title">${product.designation}</h5>
                <p class="card-text">${product.prix} €</p>
                <a href="/pages/produit.html?id=${product.id_produit}" class="btn btn-primary">Voir le produit</a>
            </div>
            `;
            productCard.classList.add("m-1");
            productsContainer.appendChild(productCard);
        });
    }).catch((error) => console.error(error));
}
afficherProduits();

