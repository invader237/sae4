function handleNavbar(size) { // size -> Taille en px à partir de laquelle le menu se cache 
    document.addEventListener("DOMContentLoaded", function() {
        let screenSize = window.matchMedia('(max-width: ' + size + 'px)');

        screenSize.addEventListener("change", toggleNavbar);
        toggleNavbar(screenSize)

        // Quand on appuie sur le bouton -> Lance la fonction
        document.querySelector(".navbar-toggler").addEventListener("click", function() {
            let nav = document.querySelector(".navbar-nav");

            if (nav.style.display === "block") { // Si il est en bloc, cela veut dire qu'il est actuellement affiché
                nav.style.display = "none"; // On le cache 
            } else {
                nav.style.display = "block"; // Sinon on le définit en tant que block (Le ul n'est plus caché)
            }
        });
    });
}

function toggleNavbar(element) {
    if (element.matches) {
        // Cache le menu de navigation et affiche le bouton si la taille d'écran est inférieure ou égale à size px
        document.querySelector(".navbar-nav").style.display = "none";
        document.querySelector(".navbar-toggler").style.display = "block";
    } else {
        // Sinon cache le bouton, affiche le menu de navigation, et réaffiche tous les liens s'ils étaient cachés auparavant
        document.querySelector(".navbar-nav").style.display = "flex";
        document.querySelector(".navbar-toggler").style.display = "none";
    }
}