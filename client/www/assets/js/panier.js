import { getCart, addToCart, deleteFromCart, deleteAllFromCart } from "./core/api/api.js";
const connected = !!localStorage.getItem("authToken");

function createCartItem(entry) {
    const { product: produit, quantity } = entry;

    const prixInitial = parseFloat(produit.product.price);
    const reduction = produit.discount || 0;
    const prixRemise = (prixInitial * (1 - reduction)).toFixed(2);
    const sousTotal = (prixRemise * quantity).toFixed(2);

    const item = document.createElement('div');

    item.innerHTML = `
    <div class="card mb-4 shadow-sm border-0 rounded-4 overflow-hidden p-3">
        <div class="row g-3">
            <div class="col-12 col-md-4 text-center">
                <img src="https://gitlab.univ-lorraine.fr/laroche5/sae401_2425/-/raw/master/serveur/img/articles/${produit.color.urlImage}?ref_type=heads" 
                     class="img-fluid rounded-3" 
                     alt="${produit.label}" 
                     style="width: 100%; max-width: 250px; height: auto; object-fit: cover; aspect-ratio: 1 / 1;">
            </div>
            <div class="col-12 col-md-8">
                <div class="d-flex flex-column justify-content-between h-100 gap-3">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <h5 class="card-title mb-2">${produit.product.label}</h5>
                            <p class="mb-2 fs-5"><strong>Couleur :</strong> ${produit.color.label || 'N/A'}</p>
                            <p class="mb-2 fs-5"><strong>Taille :</strong> ${produit.size.label || 'Standard'}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="mb-2 fs-5">
                                <strong>Prix unitaire :</strong><br>
                                ${reduction > 0 
                                    ? `<span class="text-decoration-line-through text-muted">${prixInitial.toFixed(2)} €</span> 
                                       <span class="text-danger fw-semibold ms-1">${prixRemise} €</span>` 
                                    : `<span class="fw-semibold">${prixInitial.toFixed(2)} €</span>`
                                }
                            </p>
                            <label for="quantity-${produit.id}" class="form-label mb-1 fs-5">Quantité :</label>
                            <input type="number"
                                   class="form-control form-control-sm w-auto"
                                   id="quantity-${produit.id}"
                                   value="${quantity}"
                                   min="1"
                                   step="1">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2">
                        <p class="card-text mb-0 fs-5">
                            <strong>Sous-total :</strong> 
                            <span id="subtotal-${produit.id}">${sousTotal}</span> €
                        </p>
                        <button class="btn btn-outline-danger fs-5 py-2 px-3">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;

    const removeButton = item.querySelector(".btn-outline-danger");
    removeButton.addEventListener("click", () => {
        removeItem(produit.product.id, produit.color.id, produit.size.id);
    });

    const quantityInput = item.querySelector(`#quantity-${produit.id}`);
    quantityInput.addEventListener("change", async (event) => {
        const newQuantity = parseInt(event.target.value);
        if (newQuantity > 0) {
            await updateQuantity(produit.product.id, newQuantity, produit.color.id, produit.size.id);
        }
    });

    return item;
}

async function displayCart() {
    const panierContainer = document.getElementById('panier');
    const footer = document.getElementById('footer');
    const prixTotalSpan = document.getElementById('prixTotal');

    panierContainer.innerHTML = '';

    try {
        const response = await getCart();
        const cart = response?.data?.products;

        if (!cart || cart.length === 0) {
            panierContainer.innerHTML = `
                <div class="alert alert-danger text-center" role="alert">
                    <i class="bi bi-cart-x me-2"></i>Votre panier est vide.
                </div>`;
            footer.style.display = 'none';
            return;
        }

        let total = 0;
        cart.forEach(entry => {
            const item = createCartItem(entry);
            panierContainer.appendChild(item);
            const prixProduit = parseFloat(entry.product.product.price);
            const reduction = entry.product.discount || 0;
            const prixRemise = prixProduit * (1 - reduction);
            total += prixRemise * entry.quantity;
        });

        prixTotalSpan.textContent = total.toFixed(2);
        footer.style.display = 'block';

    } catch (error) {
        console.error("Erreur lors de la récupération du panier :", error);
        panierContainer.innerHTML = '<p>Erreur lors de l\'affichage du panier.</p>';
    }
}

async function removeItem(id, color, size) {
    try {
        await deleteFromCart(id, color, size);
        await displayCart();
    } catch (error) {
        console.error("Erreur lors de la suppression de l'article :", error);
    }
}

async function updateQuantity(id, newQuantity, color, size) {
    try {
        const quantity = parseInt(newQuantity);
        if (quantity > 0) {
            await addToCart(id, quantity, size, color);
            await displayCart();
        }
    } catch (error) {
        console.error("Erreur lors de la mise à jour de la quantité :", error);
    }
}

document.getElementById('clear').addEventListener('click', async () => {
    try {
        await deleteAllFromCart();
        await displayCart();
    } catch (error) {
        console.error("Erreur lors de la suppression de tous les articles :", error);
    }
});

document.getElementById('payer').addEventListener('click', () => {
    window.location.href = './achat.html';
});

if (connected) {
    displayCart();
} else {
    window.location.href = "./login.html";
}