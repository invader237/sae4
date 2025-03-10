function componentsLoader(path, id) {
    fetch(path)
        .then(response => response.text())
        .then(data => document.getElementById(id).innerHTML = data)
        .catch(error => console.error("Erreur lors du chargement du composant :", error));
}

