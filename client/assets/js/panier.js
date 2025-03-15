document.addEventListener("DOMContentLoaded", function () {
    afficherPanier();
});

function afficherPanier() {
    fetch('PanierController.php?action=getPanier&id_utilisateur=1') // Remplace par l'ID de l'utilisateur actuel
        .then(response => response.json())
        .then(data => {
            const panierDiv = document.getElementById('panier');
            panierDiv.innerHTML = '';

            if (data.length === 0) {
                panierDiv.innerHTML = '<p>Votre panier est vide.</p>';
                document.getElementById("footer").style.display = "none";
                return;
            }

            let total = 0;
            data.forEach(item => {
                const produitDiv = document.createElement('div');
                produitDiv.classList.add('produit');
                produitDiv.innerHTML = `
                    <img src="${item.produit.url_image}" alt="${item.produit.designation}" width="100">
                    <p>${item.produit.designation}</p>
                    <p>Prix: ${item.produit.prix}€</p>
                    <p>Quantité: ${item.qte}</p>
                    <button onclick="supprimerProduit(${item.produit.id_produit}, ${item.id_taille}, ${item.id_couleur})">Supprimer</button>
                `;
                panierDiv.appendChild(produitDiv);

                total += item.produit.prix * item.qte;
            });

            document.getElementById("prixTotal").textContent = total.toFixed(2);
            document.getElementById("footer").style.display = "block";
        })
        .catch(error => console.error('Erreur lors du chargement du panier:', error));
}

function supprimerProduit(id_produit, id_taille, id_couleur) {
    fetch('PanierController.php?action=deleteProduitPanier&id_utilisateur=1', { // Remplace par l'ID de l'utilisateur
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_produit, id_taille, id_couleur })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message);
        afficherPanier(); // Recharge le panier après suppression
    })
    .catch(error => console.error('Erreur lors de la suppression:', error));
}

function clearPanier() {
    fetch('PanierController.php?action=clearPanier&id_utilisateur=1', { // Remplace par l'ID de l'utilisateur
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message);
        afficherPanier(); // Recharge le panier après vidage
    })
    .catch(error => console.error('Erreur lors du vidage:', error));
}
