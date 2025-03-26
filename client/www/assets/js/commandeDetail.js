import { getOrderById, getUser } from "./core/api/api.js";

const response = await getUser();

if (response === undefined) {
    window.location.href = "./login.html";
}

const urlParams = new URLSearchParams(window.location.search);
const orderId = urlParams.get("id");

if (!orderId) {
  console.error("Aucun ID de commande fourni dans l'URL.");
} else {
  try {
    const response = await getOrderById(orderId);
    const total = Number(response.data.total);
    const order = response.data.order;

    const userResponse = await getUser();
    const user = userResponse.data;

    document.getElementById("facture-date").innerText = new Date(order.date).toLocaleDateString("fr-FR");
    document.getElementById("facture-num").innerText = order.id;

    const tableBody = document.getElementById("table-body");
    let totalProduits = 0;

    order.products.forEach(item => {
      const label = item.product.product.label;
      const price = Number(item.product.product.price);
      const quantity = Number(item.quantity);
      const subtotal = (price * quantity).toFixed(2);

      totalProduits += price * quantity;

      const row = `
        <tr>
          <td>${label}</td>
          <td>${price.toFixed(2)} €</td>
          <td>${quantity}</td>
          <td>${subtotal} €</td>
        </tr>
      `;
      tableBody.insertAdjacentHTML("beforeend", row);
    });

    const totalProduitsFixed = totalProduits.toFixed(2);
    const prixLivraison = (total - totalProduits).toFixed(2);

    document.getElementById("total-produit").textContent = `${totalProduitsFixed} €`;
    document.getElementById("prix-livraison").textContent = `${prixLivraison} €`;
    document.getElementById("total-final").textContent = `${total.toFixed(2)} €`;

    document.getElementById("id-transac").textContent = order.idPayment || "N/A";
    document.getElementById("client-id").textContent = order.idUser || "N/A";

    document.getElementById("client-nom").textContent = `${user.idTitle} ${user.name} ${user.firstName}`;
    document.getElementById("client-email").textContent = user.email;
    document.getElementById("client-adresse").textContent = order.deliveryAddress;

    const downloadBtn = document.getElementById('download-pdf');
    if (downloadBtn) {
      downloadBtn.addEventListener('click', () => {
        const invoice = document.getElementById('invoice');
        html2canvas(invoice).then(canvas => {
          const imgData = canvas.toDataURL('image/png');
          const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
          const imgProps = pdf.getImageProperties(imgData);
          const pdfWidth = pdf.internal.pageSize.getWidth();
          const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
          pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
          pdf.save(`facture-${order.id}.pdf`);
        });
      });
    }

  } catch (error) {
    console.error("Erreur lors de la récupération des données :", error);
  }
}
