import { getCart } from "./core/api/api.js";

function createOrderSummaryItem(entry) {
    const { product: produit, quantity } = entry;
    const prixInitial = parseFloat(produit.product.price);
    const reduction = produit.discount || 0;
    const prixRemise = (prixInitial * (1 - reduction)).toFixed(2);
    const sousTotal = (prixRemise * quantity).toFixed(2);

    const item = document.createElement("div");
    item.classList.add("d-flex", "justify-content-between", "align-items-start", "border-bottom", "pb-2");

    item.innerHTML = `
        <div>
            <h6 class="mb-1">${produit.product.label}</h6>
            <p class="mb-1 small text-muted">
                Couleur : ${produit.color.label || 'N/A'}<br>
                Taille : ${produit.size.label || 'Standard'}<br>
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
        const response = await getCart();
        const cart = response?.data?.products;

        if (!cart || cart.length === 0) {
            summaryContainer.innerHTML = `
                <div class="alert alert-danger text-center" role="alert">
                    <i class="bi bi-cart-x me-2"></i>Votre commande est vide.
                </div>`;
            totalSpan.textContent = '0';
            return;
        }

        summaryContainer.innerHTML = '';
        let total = 0;

        cart.forEach(entry => {
            const item = createOrderSummaryItem(entry);
            summaryContainer.appendChild(item);

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

function updateTotalWithShipping() {
    const prixTotal = parseFloat(document.getElementById("prixTotal").textContent || 0);
    const livraisonSelect = document.getElementById("livraison");
    const selectedOption = livraisonSelect.options[livraisonSelect.selectedIndex];
    const frais = parseFloat(selectedOption.dataset.frais || 0);

    document.getElementById("livraisonFrais").textContent = frais.toFixed(2);
    document.getElementById("totalAPayer").textContent = (prixTotal + frais).toFixed(2);
}

document.addEventListener("DOMContentLoaded", () => {
    setTimeout(updateTotalWithShipping, 500);
});

document.getElementById("livraison").addEventListener("change", updateTotalWithShipping);

window.addEventListener("DOMContentLoaded", displayOrderSummary);
