import { getCart, getUser, getDelivery } from "./core/api/api.js";

function createOrderSummaryItem(entry) {
    const { product: produit, quantity } = entry;
    const prixInitial = parseFloat(produit.product.price);
    const reduction = produit.discount || 0;
    const prixRemise = (prixInitial * (1 - reduction)).toFixed(2);
    const sousTotal = (prixRemise * quantity).toFixed(2);

    const item = document.createElement("div");
    item.className = "d-flex justify-content-between align-items-start border-bottom pb-2";

    item.innerHTML = `
        <div>
            <h6 class="mb-1">${produit.product.label}</h6>
            <p class="mb-1 small text-muted">
                Couleur : ${produit.color?.label || 'N/A'}<br>
                Taille : ${produit.size?.label || 'Standard'}<br>
                Quantité : ${quantity}
            </p>
        </div>
        <div class="text-end">
            ${reduction > 0
                ? `<small class="text-decoration-line-through text-muted">${prixInitial.toFixed(2)} €</small><br>
                   <strong>${prixRemise} €</strong>`
                : `<strong>${prixInitial.toFixed(2)} €</strong>`}
            <div class="text-muted small">Sous-total : ${sousTotal} €</div>
        </div>
    `;

    return item;
}

async function displayOrderSummary() {
    const summaryContainer = document.getElementById("orderSummaryList");
    const totalSpan = document.getElementById("prixTotal");

    try {
        const { data: { products: cart = [] } = {} } = await getCart();

        if (cart.length === 0) {
            summaryContainer.innerHTML = `
                <div class="alert alert-danger text-center" role="alert">
                    <i class="bi bi-cart-x me-2"></i>Votre commande est vide.
                </div>`;
            totalSpan.textContent = '0.00';
            return;
        }

        summaryContainer.innerHTML = '';
        let total = 0;

        cart.forEach(entry => {
            summaryContainer.appendChild(createOrderSummaryItem(entry));

            const prixProduit = parseFloat(entry.product.product.price);
            const reduction = entry.product.discount || 0;
            const prixRemise = prixProduit * (1 - reduction);
            total += prixRemise * entry.quantity;
        });

        totalSpan.textContent = total.toFixed(2);

    } catch (error) {
        console.error("Erreur lors de la récupération de la commande :", error);
        summaryContainer.innerHTML = '<p class="text-danger">Erreur lors de l\'affichage de la commande.</p>';
    }
}

async function displayDeliveryOptions() {
    const livraisonSelect = document.getElementById("livraison");
    try {
        const { data: deliveryOptions = [] } = await getDelivery();

        deliveryOptions.forEach(option => {
            const { id, label, price } = option;
            const frais = parseFloat(price).toFixed(2);
            const optionElement = document.createElement("option");
            optionElement.value = id;
            optionElement.textContent = `${label} (${frais} €)`;
            optionElement.dataset.frais = frais;
            livraisonSelect.appendChild(optionElement);
        });
    } catch (error) {
        console.error("Erreur lors de la récupération des options de livraison :", error);
        livraisonSelect.innerHTML = '<option value="">Erreur de chargement</option>';
    }
}

displayDeliveryOptions();

function updateTotalWithShipping() {
    const prixTotal = parseFloat(document.getElementById("prixTotal").textContent || 0);
    const livraisonSelect = document.getElementById("livraison");
    const selectedOption = livraisonSelect.options[livraisonSelect.selectedIndex];
    const frais = parseFloat(selectedOption?.dataset?.frais || 0);

    document.getElementById("livraisonFrais").textContent = frais.toFixed(2);
    document.getElementById("totalAPayer").textContent = (prixTotal + frais).toFixed(2);
}

document.addEventListener("DOMContentLoaded", () => {
    displayOrderSummary();
    displayUser();
    setTimeout(updateTotalWithShipping, 500);
});

function displayUser() {
    getUser()
        .then(({ data: user }) => {
            document.getElementById("email").textContent = user.email;
            document.getElementById("nom").textContent = user.name;
            document.getElementById("prenom").textContent = user.firstName;
        })
        .catch(error => {
            console.error("Erreur lors de la récupération de l'utilisateur :", error);
            document.getElementById("email").textContent = 'Utilisateur inconnu';
        });
}

const map = L.map('map').setView([48.8566, 2.3522], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
    maxZoom: 19
}).addTo(map);

let marker;

async function updateMapFromAddress(address) {
    try {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`;
        const response = await fetch(url);
        const data = await response.json();

        if (data?.length > 0) {
            const { lat, lon } = data[0];
            const latNum = parseFloat(lat);
            const lonNum = parseFloat(lon);

            map.setView([latNum, lonNum], 15);

            if (!marker) {
                marker = L.marker([latNum, lonNum]).addTo(map);
            } else {
                marker.setLatLng([latNum, lonNum]);
            }

            marker.bindPopup("Adresse : " + address).openPopup();
        }
    } catch (error) {
        console.error("Erreur lors de la mise à jour de la carte :", error);
    }
}

const adresseInput = document.getElementById('adresse');
adresseInput?.addEventListener('input', () => {
    const adresse = adresseInput.value.trim();
    if (adresse.length > 5) {
        updateMapFromAddress(adresse);
    }
});

document.getElementById("livraison")?.addEventListener("change", updateTotalWithShipping);
