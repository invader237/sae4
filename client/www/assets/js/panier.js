import { getCart } from "./core/api/api.js";

function createCartItem(entry) {
    const produit = entry.product;
    const quantity = entry.quantity;

    const prixInitial = parseFloat(produit.product.price);
    const reduction = produit.discount || 0; 
    const prixRemise = (prixInitial * (1 - reduction)).toFixed(2);
    const sousTotal = (prixRemise * quantity).toFixed(2);

    const item = document.createElement('div');

    item.innerHTML = `
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="https://gitlab.univ-lorraine.fr/laroche5/sae401_2425/-/raw/master/serveur/img/articles/${produit.color.urlImage}?ref_type=heads" 
                     class="img-fluid rounded-start h-100" 
                     alt="${produit.label}" 
                     style="object-fit: cover; max-height: 180px;">
            </div>

            <div class="col-md-8">
                <div class="card-body py-2 px-3 d-flex flex-column justify-content-between h-100">
                    
                    <div class="row">
                        <div class="col-6">
                            <h6 class="card-title mb-1">${produit.product.label}</h6>
                            <p class="mb-1" style="font-size: 0.9rem;"><strong>Couleur :</strong> ${produit.color.label || 'N/A'}</p>
                            <p class="mb-1" style="font-size: 0.9rem;"><strong>Taille :</strong> ${produit.size.label || 'Standard'}</p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1" style="font-size: 0.9rem;">
                                <strong>Prix unitaire :</strong><br>
                                ${reduction > 0 
                                    ? `<span class="text-decoration-line-through text-muted">${prixInitial.toFixed(2)} €</span> 
                                       <span class="text-danger ms-1">${prixRemise} €</span>` 
                                    : `${prixInitial.toFixed(2)} €`
                                }
                            </p>

                            <label for="quantity-${produit.id}" class="form-label mb-1" style="font-size: 0.9rem;">Quantité :</label>
                            <input type="number"
                                   class="form-control form-control-sm"
                                   id="quantity-${produit.id}"
                                   value="${quantity}"
                                   min="1"
                                   step="1"
                                   style="width: 80px;"
                                   onchange="updateSubtotal(${produit.id}, this.value)">
                        </div>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <p class="card-text mb-0" style="font-size: 0.9rem;">
                            <strong>Sous-total :</strong> 
                            <span id="subtotal-${produit.id}">${sousTotal}</span> €
                        </p>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${produit.id})">Supprimer</button>
                    </div>

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
        total += parseFloat(entry.product.product.price) * entry.quantity;
    });

    prixTotalSpan.textContent = total.toFixed(2);
    footer.style.display = 'block';
}

displayCart();
