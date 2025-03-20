import { getProductByIdAndColorAndSize, getColorsByProductId, getSizesByProductId } from "./core/api/api.js";
const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get("id");
const colorDefault = await getColorsByProductId(id);
let color = colorDefault.data?.[0].id_couleur;
const sizeDefault = await getSizesByProductId(id);
let size = sizeDefault.data?.[0].id_taille;
//const connected = getUserLoginStatus();

function quantiteCommandeeValide(qtte) {
    return !(parseInt(qtte) <= 0 || isNaN(parseInt(qtte)));
}

async function imprimerSelectionCouleur(id_produit) {
    const couleurs = new Map();
    const couleursDisponibles = await getColorsByProductId(id_produit);

    couleursDisponibles.data.forEach((couleur) => {
        couleurs.set(couleur.id_couleur, couleur.libelle);
    });

    const selecteur = document.createElement("select");
    selecteur.setAttribute("id", "selectCouleur");

    couleurs.forEach((nomCouleur, idCouleur) => {
        let option = document.createElement("option");
        option.text = nomCouleur;
        option.value = idCouleur;
        if (idCouleur == color) {
            option.selected = true;
        }
        selecteur.add(option);
    });

    const couleurContainer = document.getElementById("couleur");
    couleurContainer.innerHTML = "Couleur : ";
    couleurContainer.appendChild(selecteur);

    // Écouteur d'événement pour recharger les détails selon la couleur sélectionnée
    selecteur.addEventListener("change", (event) => {
        color = event.target.value;
        getProductByIdAndColorAndSize(id_produit, color, size)
            .then((response) => afficherDetails(response.data))
            .catch((error) => console.error("Erreur lors du chargement des détails produit:", error));
    });
}

async function imprimerSelectionTaille(id_produit) {
    let tailles = new Map();
    const taillesDisponibles = await getSizesByProductId(id_produit);

    taillesDisponibles.data.forEach((taille) => {
        tailles.set(taille.id_taille, taille.libelle);
    });

    const selecteur = document.createElement("select");
    selecteur.setAttribute("id", "selectTaille");

    tailles.forEach((nomTaille, idTaille) => {
        let option = document.createElement("option");
        option.text = nomTaille;
        option.value = idTaille;
        if (idTaille == size) {
            option.selected = true;
        }
        selecteur.add(option);
    });

    const tailleContainer = document.getElementById("taille");
    tailleContainer.innerHTML = "Taille : ";
    tailleContainer.appendChild(selecteur);

    // Écouteur d'événement pour recharger les détails selon la taille sélectionnée
    selecteur.addEventListener("change", (event) => {
        size = event.target.value;
        getProductByIdAndColorAndSize(id_produit, color, size)
            .then((response) => afficherDetails(response.data))
            .catch((error) => console.error("Erreur lors du chargement des détails produit:", error));
    });
}

function boutonCommander(id_produit) {
    const bouton = document.querySelector("input[type=button]");
    bouton.addEventListener("click", (event) => {
        const nbCommandee = root.getElementById("nbrCommande").valueAsNumber;
        if (quantiteCommandeeValide(nbCommandee)) {
            /* fetch("https://devweb.iutmetz.univ-lorraine.fr/~laroche5/SAE_401/serveur/api/newPanier.php", {
                    method: "POST",
                    body: new URLSearchParams({
                        id_us: cookieValue,
                        id_prod: id_produit,
                        id_tail: size,
                        id_col: color,
                        qte_pan: nbCommandee,
                    }),
                })
                .then((reponse) => {
                    reponse.json().then((data) => {
                        if (data.status === "error") {
                            window.location.href = "https://devweb.iutmetz.univ-lorraine.fr/~trivino7u/sae4/client/www/pages/login.html";
                        } else if (data.status === "success") {
                            window.location.href = "accueil.html";
                        }
                    })
                })
                .catch((error) => { console.log(error) }); */
        };
    });
}

// Fonction pour afficher les détails du produit
function afficherDetails(product) {
    product.url_image = product.url_image.replace(" ", "%20");

    const h1Element = document.querySelector(".produitDetail h1");
    const img_prod = document.querySelector(".img_prod");
    const desc_prod = document.querySelector(".desc_prod");
    const sku = document.getElementById("sku");
    const prix = document.getElementById("prix");
    const nbrCommande = document.getElementById("nbrCommande");
    const nbrStock = document.getElementById("nbrStock");
    const prixTotal = document.getElementById("prix_tot");
    const boutonLabel = document.querySelector(".ajouterPanierLabel");

    document.title = product.designation + " - PM2";
    h1Element.innerHTML = product.designation;
    img_prod.src = `https://gitlab.univ-lorraine.fr/laroche5/sae401_2425/-/raw/master/serveur/img/articles/${product.url_image}?ref_type=heads`;
    desc_prod.innerHTML = product.description;
    sku.innerHTML = "Work in progress";
    prix.innerHTML = product.prix;
    prixTotal.innerHTML = product.prix;

    imprimerSelectionCouleur(id);
    imprimerSelectionTaille(id);

    //const nbS = getNbProductFromStock(id, color, size);
    //nbrStock.innerHTML = nbS;
    //const nbP = getNbProductFromCart(id);
    //boutonLabel.innerHTML = `Déjà ${nb} dans le panier`;

    nbrCommande.addEventListener("input", (event) => {
        const contenu = event.target.value;
        if (!quantiteCommandeeValide(contenu)) {
            event.target.style.background = "red";
            prixTotal.innerHTML = prix.innerHTML;
        } else {
            event.target.style.background = "whitesmoke";
            prixTotal.innerHTML = Math.round(prix.innerHTML * parseInt(contenu) * 100) / 100;
        }
    });

    boutonCommander(id);
}

// Charger les détails du produit au chargement de la page
getProductByIdAndColorAndSize(id, color, size)
    .then((response) => afficherDetails(response.data))
    .catch((error) => console.error("Erreur lors du chargement des détails produit:", error));