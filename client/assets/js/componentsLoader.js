fetch("../../components/footer.html")
.then(response => response.text())
.then(data=>document.getElementById("printfooter").innerHTML=data)
.catch(error=>console.error("Erreur lors de l'affichage du footer : ",error));
