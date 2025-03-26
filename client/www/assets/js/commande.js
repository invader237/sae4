import { getOrders, getUser } from "./core/api/api.js";

const response = await getUser();

if (response === undefined) {
    window.location.href = "./login.html";
}

const tableBody = document.getElementById("ordersTableBody");

const commandes = await getOrders();

commandes.data.forEach(order => {
    const row = document.createElement("tr");
    row.className = "table-hover";

    row.innerHTML = `
        <td>${order.id}</td>
        <td>${new Date(order.date).toLocaleDateString("fr-FR")}</td>
        <td>${order.idPayment}</td>
        <td>${order.deliveryAddress}</td>
        <td>${order.idDelivery}</td>
        <td>${order.total} €</td>
    `;

    row.addEventListener("click", () => {
        window.location.href = `./commandeDetail.html?id=${order.id}`;
    });

    tableBody.appendChild(row);
});
