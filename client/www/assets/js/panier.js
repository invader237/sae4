import { getCart } from "./core/api/api.js";

function createCartItem(entry) {
    const produit = entry.product;
    console.log(produit);
    const quantity = entry.quantity;

    const item = document.createElement('div');
    item.className = 'card shadow-sm';

    item.innerHTML = `
        <div class="row g-0">
            <div class="col-md-4">
                <img src="https://gitlab.univ-lorraine.fr/laroche5/sae401_2425/-/raw/master/serveur/img/articles/${produit.color.urlImage}?ref_type=heads" 
                     class="img-fluid rounded-start h-100" 
                     alt="${produit.label}" 
                     style="object-fit: cover; max-height: 180px;">
            </div>
            <div class="col-md-8">
                <div class="card-body py-2 px-3 d-flex flex-column justify-content-between h-100">
                    <div>
                        <h6 class="card-title mb-1">${produit.product.label}</h6>
                        <p class="card-text mb-1" style="font-size: 0.9rem;">Prix : ${parseFloat(produit.product.price).toFixed(2)} €</p>
                        <p class="card-text mb-1" style="font-size: 0.9rem;">Quantité : ${quantity}</p>
                        <p class="card-text mb-1" style="font-size: 0.9rem;"><strong>Sous-total :</strong> ${(parseFloat(produit.product.price) * quantity).toFixed(2)} €</p>
                    </div>
                    <div class="text-end mt-2">
                        <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${produit.id})">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    return item;
}

async function displayCart() {
    const panierContainer = document.getElementById('panier');
    const footer = document.getElementById('footer');
    const prixTotalSpan = document.getElementById('prixTotal');

    panierContainer.innerHTML = ''; 

    const response = await getCart();
    const cart = response?.data?.products;

    if (!cart || cart.length === 0) {
        panierContainer.innerHTML = '<p>Votre panier est vide.</p>';
        footer.style.display = 'none';
        return;
    }

    let total = 0;

    cart.forEach(entry => {
        const item = createCartItem(entry);
        panierContainer.appendChild(item);
        total += parseFloat(entry.product.price) * entry.quantity;
    });

    prixTotalSpan.textContent = total.toFixed(2);
    footer.style.display = 'block';
}

displayCart();
