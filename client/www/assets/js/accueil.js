<<<<<<< HEAD:client/www/assets/js/accueil.js
import { getAllProducts } from "./core/api/api.js";
console.log("ouk");
async function afficherTousLesProduits() {
    try {
        let produit = await getAllProducts();
        console.log(produit);
    } catch (error) {
        console.error("Erreur lors de la récupération des produits :", error);
    }
}

afficherTousLesProduits();

/*export function imprimerUnProduit(produit) {
=======
function afficherTousLesProduits() {
    const urlParams = new URLSearchParams(window.location.search);
    const taille = urlParams.get("taille");
    const couleur = urlParams.get("idCouleur");

    const produitGenerique =
        "https://devweb.iutmetz.univ-lorraine.fr/~laroche5/SAE_401/serveur/api/getGenericProduits.php";
    const produitComplet =
        "https://devweb.iutmetz.univ-lorraine.fr/~laroche5/SAE_401/serveur/api/getProduits.php";
    const url = taille || couleur ? produitComplet : produitGenerique;

    return fetch(url)
        .then((reponse) => reponse.json())
        .then((data) => {
            imprimerTousLesProduits(data.data);
        })
        .catch((error) => console.log(error));
}

export function imprimerUnProduit(produit) {
>>>>>>> c4979ee (:construction: WIP):client/assets/js/accueil.js

    let path = produit["path_img"] ?
        "https://devweb.iutmetz.univ-lorraine.fr/~laroche5/SAE_401/serveur/img/articles/" + produit["path_img"] :
        "https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png";
    let produitElement = document.createElement("produit-generique");
    produitElement.classList.add("col-xs-12");
    produitElement.classList.add("col-sm-6");
    produitElement.classList.add("col-md-4");
    produitElement.classList.add("col-lg-3");
    produitElement.classList.add("col-xl-2");
    produitElement.classList.add("descProduit");
    produitElement.setAttribute("name", produit["nom_prod"]);
    produitElement.setAttribute("prix", produit["prix_unit"]);
    produitElement.setAttribute("id", produit["id_prod"]);
    produitElement.setAttribute("id_col", produit["id_col"]);
    produitElement.setAttribute("path_img", path);
    return produitElement;
}

function imprimerTousLesProduits(produits) {
    const urlParams = new URLSearchParams(window.location.search);
    const recherche = urlParams.get("search");
    const categorie = urlParams.get("idCategorie");
    const taille = urlParams.get("idTaille");
    const couleur = urlParams.get("idCouleur");
    const id_us = cookieValue;
    if (recherche) {
        produits = produitsRecherche(recherche, produits);
    }
    if (categorie) {
        produits = produitsCategorie(categorie, produits);
    }
    if (taille) {
        produits = produitsTaille(taille, produits);
    }
    if (couleur) {
        produits = produitsCouleur(couleur, produits);
    }

    const listeProd = document.querySelector(".produits");
    produits.forEach((produit) => {
        const produitElement = imprimerUnProduit(produit);
        document.querySelector(".produits").appendChild(produitElement);
    });
    traiterFavori(id_us);
}
afficherTousLesProduits();
*/
afficherTousLesProduits();
