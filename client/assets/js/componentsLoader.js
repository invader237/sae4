fetch("../../components/header.html")
        .then(response => response.text())
        .then(data => document.getElementById("printHeader").innerHTML = data)
        .catch(error => console.error("Erreur lors du chargement du header :", error));