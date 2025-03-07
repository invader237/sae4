function footer(link,id){
    fetch(link)
    .then(response => response.text())
    .then(data=>document.getElementById(id).innerHTML=data)
    .catch(error=>console.error("Erreur lors de l'affichage du footer : ",error));
}