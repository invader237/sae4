import { getProductByIdAndColorAndSize, getColorsByProductId, getSizesByProductId, getUser, getSku } from "./core/api/api.js";

const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get("id");
const connected = !!localStorage.getItem("authToken");

// Initialisation par défaut de la couleur et de la taille
let color, size;

(async () => {
    const colorDefault = await getColorsByProductId(id);
    color = colorDefault.data?.[0]?.id;

    const sizeDefault = await getSizesByProductId(id);
    size = sizeDefault.data?.[0]?.id;

    try {
        const response = await getProductByIdAndColorAndSize(id, color, size);
        afficherDetails(response.data);
    } catch (error) {
        console.error("Erreur lors du chargement des détails produit:", error);
    }
})();

function quantiteCommandeeValide(qtte) {
    const parsed = parseInt(qtte);
    return !isNaN(parsed) && parsed > 0;
}

async function imprimerSelectionCouleur(id_produit, selectedColor) {
    const couleurs = await getColorsByProductId(id_produit);
    const selecteur = document.createElement("select");
    selecteur.id = "selectCouleur";

    couleurs.data.forEach((couleurOption) => {
        const option = document.createElement("option");
        option.textContent = couleurOption.label;
        option.value = couleurOption.id;
        if (couleurOption.id == selectedColor) option.selected = true;
        selecteur.appendChild(option);
    });

    const couleurContainer = document.getElementById("couleur");
    couleurContainer.textContent = "Couleur : ";
    couleurContainer.appendChild(selecteur);

    selecteur.addEventListener("change", async (event) => {
        color = event.target.value;
        try {
            const response = await getProductByIdAndColorAndSize(id_produit, color, size);
            afficherDetails(response.data, color, size);
        } catch (error) {
            console.error("Erreur lors du rechargement avec la couleur:", error);
        }
    });
}

async function imprimerSelectionTaille(id_produit, selectedSize) {
    const tailles = await getSizesByProductId(id_produit);
    const selecteur = document.createElement("select");
    selecteur.id = "selectTaille";

    tailles.data.forEach((tailleOption) => {
        const option = document.createElement("option");
        option.textContent = tailleOption.label;
        option.value = tailleOption.id;
        if (tailleOption.id == selectedSize) option.selected = true;
        selecteur.appendChild(option);
    });

    const tailleContainer = document.getElementById("taille");
    tailleContainer.textContent = "Taille : ";
    tailleContainer.appendChild(selecteur);

    selecteur.addEventListener("change", async (event) => {
        size = event.target.value;
        try {
            const response = await getProductByIdAndColorAndSize(id_produit, color, size);
            afficherDetails(response.data, color, size);
        } catch (error) {
            console.error("Erreur lors du rechargement avec la taille:", error);
        }
    });
}

function boutonCommander(id_produit) {
    const bouton = document.querySelector("input[type=button]");
    bouton.addEventListener("click", () => {
        if (connected) {
            const nbCommandee = document.getElementById("nbrCommande").valueAsNumber;
            if (quantiteCommandeeValide(nbCommandee)) {
                addToCart(id_produit, nbCommandee, size, color);
                console.log(`Commande de ${nbCommandee} article(s) pour le produit ${id_produit}, couleur ${color}, taille ${size}`);
            } else {
                alert("Veuillez entrer une quantité valide.");
            }
        } else {
            window.location.href = "./login.html";
        }
    });
}

async function afficherDetails(product, selectedColor = color, selectedSize = size) {
    if (!product) return;

    const sku = await getSku(id, size, color);

    product.urlImage = product.urlImage.replace(/ /g, "%20");

    document.title = `${product.label} - PM2`;
    document.querySelector(".produitDetail h1").textContent = product.label;
    document.querySelector(".img_prod").src = `https://gitlab.univ-lorraine.fr/laroche5/sae401_2425/-/raw/master/serveur/img/articles/${product.urlImage}?ref_type=heads`;
    document.querySelector(".desc_prod").textContent = product.description;
    document.getElementById("sku").textContent = "Work in progress";
    document.getElementById("prix").textContent = product.price;
    document.getElementById("prix_tot").textContent = product.price;

    document.getElementById("nbrStock").textContent = sku.data.stock;
    document.getElementById("sku").textContent = sku.data.label;

    imprimerSelectionCouleur(product.id, selectedColor);
    imprimerSelectionTaille(product.id, selectedSize);
    boutonCommander(product.id);

    const nbrCommande = document.getElementById("nbrCommande");
    const prixTotal = document.getElementById("prix_tot");
    const prix = parseFloat(product.price);

    nbrCommande.addEventListener("input", (event) => {
        const val = parseInt(event.target.value);
        if (quantiteCommandeeValide(val)) {
            event.target.style.background = "whitesmoke";
            prixTotal.textContent = (prix * val).toFixed(2);
        } else {
            event.target.style.background = "red";
            prixTotal.textContent = prix.toFixed(2);
        }
    });
}
