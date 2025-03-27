import { getProductByIdAndColorAndSize, getColorsByProductId, getSizesByProductId, getUser, getSku, addToCart } from "./core/api/api.js";
import { favoritesManager } from "./favorites.js";

const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get("id");

let color, size;
const button=document.querySelector(".add-to-fav-btn");

(async () => {
    const colorDefault = await getColorsByProductId(id);
    color = colorDefault.data?.[0]?.id;

    const sizeDefault = await getSizesByProductId(id);
    size = sizeDefault.data?.[0]?.id;

    button.data_product_id=id;
    button.data_size_id=size;
    button.data_color_id=color;

    try {
        const response = await getProductByIdAndColorAndSize(id, color, size);
        afficherDetails(response.data);
    } catch (error) {
        console.error("Erreur lors du chargement des détails produit:", error);
    }
})();

function quantiteCommandeeValide(qtte, stock) {
  const parsed = parseInt(qtte);
  return !isNaN(parsed) && parsed > 0 && parsed <= stock;
}

async function creerSelect(id, options, selectedValue, labelText, onChangeHandler) {
  const selecteur = document.createElement("select");
  selecteur.id = id;
  selecteur.className = "form-select mb-2";
  selecteur.setAttribute("style", "min-height: 40px;");

  options.forEach(optionData => {
    const option = document.createElement("option");
    option.textContent = optionData.label;
    option.value = optionData.id;
    if (optionData.id == selectedValue) option.selected = true;
    selecteur.appendChild(option);
  });

  const container = document.getElementById(id === "selectCouleur" ? "couleur" : "taille");
  container.textContent = "";
  container.appendChild(selecteur);

  selecteur.addEventListener("change", onChangeHandler);
}

async function imprimerSelectionCouleur(id_produit, selectedColor) {
  const couleurs = await getColorsByProductId(id_produit);
  creerSelect("selectCouleur", couleurs.data, selectedColor, "Couleur", async event => {
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
  creerSelect("selectTaille", tailles.data, selectedSize, "Taille", async event => {
    size = event.target.value;
    try {
      const response = await getProductByIdAndColorAndSize(id_produit, color, size);
      afficherDetails(response.data, color, size);
    } catch (error) {
      console.error("Erreur lors du rechargement avec la taille:", error);
    }
  });
}

async function boutonCommander(id_produit, stockDispo) {
  const bouton = document.querySelector("#ajouterPanier");
  bouton.replaceWith(bouton.cloneNode(true));
  const newButton = document.querySelector("#ajouterPanier");

  const user = await getUser();

  newButton.addEventListener("click", () => {
    if (user !== undefined) {
      const nbCommandee = document.getElementById("nbrCommande").valueAsNumber;
      if (quantiteCommandeeValide(nbCommandee, stockDispo)) {
        addToCart(id_produit, nbCommandee, size, color);
        alert(`Produit ajouté au panier : ${nbCommandee} article(s)`);
        console.log(`Commande de ${nbCommandee} article(s) pour le produit ${id_produit}, couleur ${color}, taille ${size}`);
      } else {
        alert("Veuillez entrer une quantité valide (inférieure ou égale au stock disponible).");
      }
    } else {
      window.location.href = "./login.html";
    }
  });
}

function mettreAJourPrixTotal(prix, stock) {
  const nbrCommande = document.getElementById("nbrCommande");
  const prixTotal = document.getElementById("prix_tot");

  nbrCommande.addEventListener("input", event => {
    const val = parseInt(event.target.value);
    if (quantiteCommandeeValide(val, stock)) {
      event.target.style.background = "whitesmoke";
      prixTotal.textContent = (prix * val).toFixed(2);
    } else {
      event.target.style.background = "red";
      prixTotal.textContent = prix.toFixed(2);
    }
  });
}

async function afficherDetails(product, selectedColor = color, selectedSize = size) {
  if (!product) return;

  const sku = await getSku(id, size, color);

  const imageUrl = encodeURIComponent(product.urlImage);
  document.title = `${product.label} - PM2`;

  const titleEl = document.querySelector("h1.card-title");
  const imageEl = document.getElementById("imgProduit");

  if (titleEl) titleEl.textContent = product.label;
  if (imageEl) imageEl.src = `https://gitlab.univ-lorraine.fr/laroche5/sae401_2425/-/raw/master/serveur/img/articles/${imageUrl}?ref_type=heads`;

  document.querySelector(".desc_prod").textContent = product.description;
  document.getElementById("sku").textContent = sku.data.label;
  document.getElementById("prix").textContent = product.price;
  document.getElementById("prix_tot").textContent = product.price;

  document.getElementById("nbrStock").textContent = sku.data.stock;
  document.getElementById("nbrCommande").max = sku.data.stock;

  imprimerSelectionCouleur(product.id, selectedColor);
  imprimerSelectionTaille(product.id, selectedSize);
  boutonCommander(product.id, sku.data.stock);
  mettreAJourPrixTotal(parseFloat(product.price), sku.data.stock);

  favoritesManager.updateFavoriteButtons();
  favoritesManager.loadDetails();
}

const addtoFavButton = document.querySelector(".add-to-fav-btn");
addtoFavButton.addEventListener("click", async (event)=> {
    event.preventDefault();
    const user = await getUser();
    if (user !==undefined) {
        try {
            favoritesManager.toggleDetails(id, size, color, addtoFavButton);
        } catch (error) {
            console.error("Erreur lors de l'ajout comme favori : ", error);
        }
    }   else {
        window.location.href="./login.html";
    }
})
